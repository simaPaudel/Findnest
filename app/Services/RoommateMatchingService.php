<?php

namespace App\Services;

use App\Models\RoommatePreference;
use App\Models\User;

class RoommateMatchingService
{
    /**
     * Scoring weights for each compatibility criterion
     */
    private const WEIGHTS = [
        'budget' => 0.20,
        'location' => 0.15,
        'cleanliness' => 0.10,
        'sleep_schedule' => 0.10,
        'study_habits' => 0.10,
        'smoking' => 0.10,
        'alcohol' => 0.05,
        'gender' => 0.10,
        'age' => 0.05,
        'interests' => 0.05,
    ];

    /**
     * Get top 5 roommate matches for a user based on preferences
     *
     * @param int $userId
     * @return array
     */
    public function getMatchesForUser($userId)
    {
        // Fetch the current user's preferences
        $userPreferences = RoommatePreference::where('user_id', $userId)->first();

        // If user has no preferences, return empty array
        if (!$userPreferences) {
            return [];
        }

        // Fetch all other users' preferences (exclude the current user)
        $otherPreferences = RoommatePreference::where('user_id', '!=', $userId)->get();

        $matches = [];

        // Score each user
        foreach ($otherPreferences as $otherPreference) {
            $score = $this->calculateCompatibilityScore($userPreferences, $otherPreference);

            $matches[] = [
                'user_id' => $otherPreference->user_id,
                'compatibility_score' => round($score, 2),
            ];
        }

        // Sort matches by score in descending order
        usort($matches, function ($a, $b) {
            return $b['compatibility_score'] <=> $a['compatibility_score'];
        });

        // Return top 5 matches
        return array_slice($matches, 0, 5);
    }

    /**
     * Calculate compatibility score between two users based on their preferences
     *
     * @param RoommatePreference $userPref
     * @param RoommatePreference $otherPref
     * @return float
     */
    private function calculateCompatibilityScore($userPref, $otherPref)
    {
        $totalScore = 0;

        // Budget range match
        $totalScore += $this->scoreBudgetMatch($userPref->budget_range, $otherPref->budget_range) * self::WEIGHTS['budget'];

        // Location match
        $totalScore += $this->scoreLocationMatch($userPref->preferred_location, $otherPref->preferred_location) * self::WEIGHTS['location'];

        // Cleanliness level match
        $totalScore += $this->scoreMatch($userPref->cleanliness_level, $otherPref->cleanliness_level) * self::WEIGHTS['cleanliness'];

        // Sleep schedule match
        $totalScore += $this->scoreMatch($userPref->sleep_schedule, $otherPref->sleep_schedule) * self::WEIGHTS['sleep_schedule'];

        // Study habits match
        $totalScore += $this->scoreMatch($userPref->study_habits, $otherPref->study_habits) * self::WEIGHTS['study_habits'];

        // Smoking preference match
        $totalScore += $this->scoreMatch($userPref->smoking_preference, $otherPref->smoking_preference) * self::WEIGHTS['smoking'];

        // Alcohol preference match
        $totalScore += $this->scoreMatch($userPref->alcohol_preference, $otherPref->alcohol_preference) * self::WEIGHTS['alcohol'];

        // Gender preference match
        $totalScore += $this->scoreGenderPreferenceMatch($userPref, $otherPref) * self::WEIGHTS['gender'];

        // Age range overlap
        $totalScore += $this->scoreAgeRangeMatch($userPref, $otherPref) * self::WEIGHTS['age'];

        // Interests similarity
        $totalScore += $this->scoreInterestsSimilarity($userPref->interests, $otherPref->interests) * self::WEIGHTS['interests'];

        // Normalize score to be between 0 and 1
        return min($totalScore, 1.0);
    }

    /**
     * Score budget range match
     * Returns 1.0 if ranges overlap, 0.0 if they don't
     *
     * @param string|null $budget1
     * @param string|null $budget2
     * @return float
     */
    private function scoreBudgetMatch($budget1, $budget2)
    {
        if ($budget1 === null || $budget2 === null) {
            return 0.5; // Neutral score for missing data
        }

        // Check if budget preferences are compatible
        // Assuming budget_range is stored as strings like "5000-10000"
        $budgets1 = $this->parseBudgetRange($budget1);
        $budgets2 = $this->parseBudgetRange($budget2);

        if (!$budgets1 || !$budgets2) {
            return 0.5;
        }

        // Check if ranges overlap
        $min1 = $budgets1['min'];
        $max1 = $budgets1['max'];
        $min2 = $budgets2['min'];
        $max2 = $budgets2['max'];

        // If they overlap, return 1.0
        if ($min1 <= $max2 && $min2 <= $max1) {
            return 1.0;
        }

        // If they don't overlap, check proximity (partial match)
        if ($min1 > $max2) {
            $diff = $min1 - $max2;
        } else {
            $diff = $min2 - $max1;
        }

        // Return a score based on proximity
        return max(0, 1.0 - ($diff / 50000)); // Normalize by a factor
    }

    /**
     * Parse budget range string
     *
     * @param string $budgetStr
     * @return array|null
     */
    private function parseBudgetRange($budgetStr)
    {
        if (strpos($budgetStr, '-') !== false) {
            [$min, $max] = explode('-', $budgetStr);
            return [
                'min' => (int)trim($min),
                'max' => (int)trim($max),
            ];
        }

        return null;
    }

    /**
     * Score location match
     * Returns 1.0 if locations are the same, 0.0 if different
     *
     * @param string|null $location1
     * @param string|null $location2
     * @return float
     */
    private function scoreLocationMatch($location1, $location2)
    {
        if ($location1 === null || $location2 === null) {
            return 0.5; // Neutral score for missing data
        }

        // Normalize to lowercase for comparison
        $location1 = strtolower(trim($location1));
        $location2 = strtolower(trim($location2));

        return $location1 === $location2 ? 1.0 : 0.0;
    }

    /**
     * Generic match scoring for exact string matches
     * Returns 1.0 if values match, 0.0 if they don't
     *
     * @param mixed $value1
     * @param mixed $value2
     * @return float
     */
    private function scoreMatch($value1, $value2)
    {
        if ($value1 === null || $value2 === null) {
            return 0.5; // Neutral score for missing data
        }

        return $value1 === $value2 ? 1.0 : 0.0;
    }

    /**
     * Score gender preference match
     * Checks if preferences are compatible
     *
     * @param RoommatePreference $pref1
     * @param RoommatePreference $pref2
     * @return float
     */
    private function scoreGenderPreferenceMatch($pref1, $pref2)
    {
        $gender1 = $pref1->gender_preference;
        $gender2 = $pref2->gender_preference;

        if ($gender1 === null || $gender2 === null) {
            return 0.5; // Neutral score for missing data
        }

        // Check mutual compatibility
        // "any" is compatible with everything
        if ($gender1 === 'any' || $gender2 === 'any') {
            return 1.0;
        }

        // Same gender preference
        if ($gender1 === $gender2) {
            return 1.0;
        }

        // Different preferences (incompatible)
        return 0.0;
    }

    /**
     * Score age range overlap
     * Returns 1.0 if age ranges overlap, scaled down if they don't
     *
     * @param RoommatePreference $pref1
     * @param RoommatePreference $pref2
     * @return float
     */
    private function scoreAgeRangeMatch($pref1, $pref2)
    {
        $min1 = $pref1->age_range_min;
        $max1 = $pref1->age_range_max;
        $min2 = $pref2->age_range_min;
        $max2 = $pref2->age_range_max;

        // If any age range is null, return neutral score
        if ($min1 === null || $max1 === null || $min2 === null || $max2 === null) {
            return 0.5;
        }

        // Check if ranges overlap
        if ($min1 <= $max2 && $min2 <= $max1) {
            return 1.0;
        }

        // If they don't overlap, return partial score based on proximity
        if ($min1 > $max2) {
            $gap = $min1 - $max2;
        } else {
            $gap = $min2 - $max1;
        }

        // Scaled down score based on age gap
        return max(0, 1.0 - ($gap / 50));
    }

    /**
     * Calculate interests similarity
     * Based on common interests / total unique interests
     *
     * @param string|null $interests1
     * @param string|null $interests2
     * @return float
     */
    private function scoreInterestsSimilarity($interests1, $interests2)
    {
        if ($interests1 === null || $interests2 === null) {
            return 0.5; // Neutral score for missing data
        }

        // Parse interests (comma-separated values)
        $interests1Array = array_map('trim', explode(',', $interests1));
        $interests2Array = array_map('trim', explode(',', $interests2));

        // Remove empty strings
        $interests1Array = array_filter($interests1Array);
        $interests2Array = array_filter($interests2Array);

        // If either has no interests, return neutral score
        if (empty($interests1Array) || empty($interests2Array)) {
            return 0.5;
        }

        // Calculate common interests
        $commonInterests = array_intersect(
            array_map('strtolower', $interests1Array),
            array_map('strtolower', $interests2Array)
        );

        // Calculate total unique interests
        $uniqueInterests = array_unique(array_merge(
            array_map('strtolower', $interests1Array),
            array_map('strtolower', $interests2Array)
        ));

        // Return similarity score
        $commonCount = count($commonInterests);
        $totalCount = count($uniqueInterests);

        return $totalCount > 0 ? $commonCount / $totalCount : 0.0;
    }
}
