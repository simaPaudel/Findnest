<?php

namespace App\Services;

use App\Models\RoommatePreference;
use App\Models\User;

class RoommateMatchingService
{
    /**
     * Compatibility weights. Total = 100.
     */
    private const WEIGHTS = [
        'budget' => 20,
        'location' => 20,
        'cleanliness' => 15,
        'sleep' => 10,
        'study' => 10,
        'smoking' => 10,
        'alcohol' => 5,
        'age' => 5,
        'interests' => 5,
    ];

    /**
     * Canonical entry point for roommate matching.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getRoommateMatches(int $userId): array
    {
        $strictMatches = $this->buildMatches($userId, true);
        if (!empty($strictMatches)) {
            return $strictMatches;
        }

        return $this->buildMatches($userId, false);
    }

    /**
     * Backward-compatible alias for existing callers.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getMatchesForUser(int $userId): array
    {
        return $this->getRoommateMatches($userId);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildMatches(int $userId, bool $enforceGenderFilter): array
    {
        $currentPreference = RoommatePreference::queryForUserId($userId)->first();
        $currentUser = User::find($userId);

        if (!$currentPreference || !$currentUser) {
            return [];
        }

        $candidatePreferences = RoommatePreference::query()->get();
        $matches = [];

        foreach ($candidatePreferences as $candidatePreference) {
            $candidateUserId = RoommatePreference::resolveUserId($candidatePreference);

            if ($candidateUserId === null || $candidateUserId === $userId) {
                continue;
            }

            $candidateUser = User::find($candidateUserId);
            if (!$candidateUser) {
                continue;
            }

            if ($enforceGenderFilter && !$this->passesGenderFilter($currentUser, $currentPreference, $candidateUser, $candidatePreference)) {
                continue;
            }

            $criteria = [
                'budget' => $this->evaluateBudgetMatch($currentPreference->budget_range, $candidatePreference->budget_range),
                'location' => $this->evaluateLocationMatch($currentPreference->preferred_location, $candidatePreference->preferred_location),
                'cleanliness' => $this->evaluateOrderedMatch(
                    $this->normalizePreferenceValue('cleanliness', $currentPreference->cleanliness_level),
                    $this->normalizePreferenceValue('cleanliness', $candidatePreference->cleanliness_level),
                    ['very_clean', 'clean', 'moderate', 'relaxed'],
                    'Cleanliness preferences match',
                    'Cleanliness preferences are close'
                ),
                'sleep' => $this->evaluateOrderedMatch(
                    $this->normalizePreferenceValue('sleep', $currentPreference->sleep_schedule),
                    $this->normalizePreferenceValue('sleep', $candidatePreference->sleep_schedule),
                    ['early_bird', 'flexible', 'night_owl'],
                    'Sleep schedules match',
                    'Sleep schedules are close'
                ),
                'study' => $this->evaluateOrderedMatch(
                    $this->normalizePreferenceValue('study', $currentPreference->study_habits),
                    $this->normalizePreferenceValue('study', $candidatePreference->study_habits),
                    ['quiet', 'moderate', 'social'],
                    'Study habits match',
                    'Study habits are close'
                ),
                'smoking' => $this->evaluateOrderedMatch(
                    $this->normalizePreferenceValue('smoking', $currentPreference->smoking_preference),
                    $this->normalizePreferenceValue('smoking', $candidatePreference->smoking_preference),
                    ['no', 'outside_only', 'yes'],
                    'Smoking preferences match',
                    'Smoking preferences are flexible'
                ),
                'alcohol' => $this->evaluateOrderedMatch(
                    $this->normalizePreferenceValue('alcohol', $currentPreference->alcohol_preference),
                    $this->normalizePreferenceValue('alcohol', $candidatePreference->alcohol_preference),
                    ['no', 'occasionally', 'yes'],
                    'Alcohol preferences match',
                    'Alcohol preferences are flexible'
                ),
                'age' => $this->evaluateAgeMatch(
                    $currentPreference->age_range_min,
                    $currentPreference->age_range_max,
                    $candidatePreference->age_range_min,
                    $candidatePreference->age_range_max
                ),
                'interests' => $this->evaluateInterestsMatch(
                    $currentPreference->interests,
                    $candidatePreference->interests
                ),
            ];

            $matches[] = [
                'user_id' => $candidateUser->id,
                'name' => $candidateUser->name,
                'email' => $candidateUser->email,
                'profile_photo' => $candidateUser->profile_photo,
                'gender' => $candidateUser->gender,
                'bio' => $candidateUser->bio,
                'preferred_location' => $candidatePreference->preferred_location,
                'budget_range' => $candidatePreference->budget_range,
                'preferences' => [
                    'budget_range' => $candidatePreference->budget_range,
                    'preferred_location' => $candidatePreference->preferred_location,
                    'gender_preference' => $candidatePreference->gender_preference,
                    'cleanliness_level' => $candidatePreference->cleanliness_level,
                    'sleep_schedule' => $candidatePreference->sleep_schedule,
                    'study_habits' => $candidatePreference->study_habits,
                    'smoking_preference' => $candidatePreference->smoking_preference,
                    'alcohol_preference' => $candidatePreference->alcohol_preference,
                    'age_range_min' => $candidatePreference->age_range_min,
                    'age_range_max' => $candidatePreference->age_range_max,
                    'interests' => $this->normalizeInterestList($candidatePreference->interests),
                ],
                'compatibility_score' => (int) round($this->calculateWeightedScore($criteria)),
                'reasons' => $this->buildReasons($criteria),
                'gender_match_mode' => $enforceGenderFilter ? 'strict' : 'fallback',
            ];
        }

        usort($matches, static function (array $left, array $right): int {
            return $right['compatibility_score'] <=> $left['compatibility_score'];
        });

        return $matches;
    }

    private function passesGenderFilter(
        User $currentUser,
        RoommatePreference $currentPreference,
        User $candidateUser,
        RoommatePreference $candidatePreference
    ): bool {
        $currentGenderPreference = $this->normalizePreferenceGender($currentPreference->gender_preference);
        $candidateGenderPreference = $this->normalizePreferenceGender($candidatePreference->gender_preference);
        $currentGender = $this->normalizeUserGender($currentUser->gender);
        $candidateGender = $this->normalizeUserGender($candidateUser->gender);

        if ($currentGenderPreference !== 'any' && $candidateGender !== $currentGenderPreference) {
            return false;
        }

        if ($candidateGenderPreference !== 'any' && $currentGender !== $candidateGenderPreference) {
            return false;
        }

        return true;
    }

    /**
     * @param array<string, array{score: float, reason: ?string}> $criteria
     */
    private function calculateWeightedScore(array $criteria): float
    {
        $score = 0.0;

        foreach (self::WEIGHTS as $key => $weight) {
            $criterionScore = (float) ($criteria[$key]['score'] ?? 0);
            $score += $criterionScore * $weight;
        }

        return min(100.0, max(0.0, $score));
    }

    /**
     * @param array<string, array{score: float, reason: ?string}> $criteria
     * @return array<int, string>
     */
    private function buildReasons(array $criteria): array
    {
        $reasons = [];

        foreach ($criteria as $criterion) {
            $score = (float) ($criterion['score'] ?? 0);
            $reason = $criterion['reason'] ?? null;

            if ($score > 0 && is_string($reason) && $reason !== '') {
                $reasons[] = [
                    'score' => $score,
                    'reason' => $reason,
                ];
            }
        }

        if (empty($reasons)) {
            return [];
        }

        usort($reasons, static function (array $left, array $right): int {
            return $right['score'] <=> $left['score'];
        });

        $uniqueReasons = [];
        foreach ($reasons as $reason) {
            if (!in_array($reason['reason'], $uniqueReasons, true)) {
                $uniqueReasons[] = $reason['reason'];
            }
        }

        return array_slice($uniqueReasons, 0, 3);
    }

    /**
     * Budget compatibility:
     * - full points when ranges overlap or are identical
     * - half points when ranges are close
     * - zero otherwise
     *
     * @return array{score: float, reason: ?string}
     */
    private function evaluateBudgetMatch(?string $currentBudget, ?string $candidateBudget): array
    {
        $currentRange = $this->parseBudgetRange($currentBudget);
        $candidateRange = $this->parseBudgetRange($candidateBudget);

        if (!$currentRange || !$candidateRange) {
            return ['score' => 0.0, 'reason' => null];
        }

        if ($currentRange['min'] <= $candidateRange['max'] && $candidateRange['min'] <= $currentRange['max']) {
            return ['score' => 1.0, 'reason' => 'Budget ranges overlap'];
        }

        $gap = $currentRange['min'] > $candidateRange['max']
            ? $currentRange['min'] - $candidateRange['max']
            : $candidateRange['min'] - $currentRange['max'];

        $threshold = max(5000, (int) round(max($currentRange['max'], $candidateRange['max']) * 0.1));

        if ($gap <= $threshold) {
            return ['score' => 0.5, 'reason' => 'Budget ranges are close'];
        }

        return ['score' => 0.0, 'reason' => null];
    }

    /**
     * @return array{min: int, max: int}|null
     */
    private function parseBudgetRange(?string $budget): ?array
    {
        if (!$budget) {
            return null;
        }

        preg_match_all('/\d[\d,]*/', $budget, $matches);
        $numbers = array_map(
            static fn (string $value): int => (int) str_replace(',', '', $value),
            $matches[0] ?? []
        );

        if (empty($numbers)) {
            return null;
        }

        sort($numbers);

        return [
            'min' => $numbers[0],
            'max' => $numbers[count($numbers) - 1],
        ];
    }

    /**
     * Location compatibility:
     * - full points for exact match
     * - half points for partial overlap
     * - zero otherwise
     *
     * @return array{score: float, reason: ?string}
     */
    private function evaluateLocationMatch(?string $currentLocation, ?string $candidateLocation): array
    {
        if (!$currentLocation || !$candidateLocation) {
            return ['score' => 0.0, 'reason' => null];
        }

        $normalizedCurrent = $this->normalizeText($currentLocation);
        $normalizedCandidate = $this->normalizeText($candidateLocation);

        if ($normalizedCurrent === $normalizedCandidate) {
            return ['score' => 1.0, 'reason' => 'Preferred locations match'];
        }

        $currentTokens = $this->tokenizeLocation($normalizedCurrent);
        $candidateTokens = $this->tokenizeLocation($normalizedCandidate);
        $sharedTokens = array_values(array_intersect($currentTokens, $candidateTokens));

        if (!empty($sharedTokens)) {
            return ['score' => 0.5, 'reason' => 'Preferred locations overlap'];
        }

        if (
            $normalizedCurrent !== '' &&
            $normalizedCandidate !== '' &&
            (
                str_contains($normalizedCurrent, $normalizedCandidate) ||
                str_contains($normalizedCandidate, $normalizedCurrent)
            )
        ) {
            return ['score' => 0.5, 'reason' => 'Preferred areas overlap'];
        }

        return ['score' => 0.0, 'reason' => null];
    }

    /**
     * Ordered compatibility for preference fields with nearby values.
     *
     * @param array<int, string> $ordering
     * @return array{score: float, reason: ?string}
     */
    private function evaluateOrderedMatch(
        ?string $currentValue,
        ?string $candidateValue,
        array $ordering,
        string $exactReason,
        string $partialReason
    ): array {
        if (!$currentValue || !$candidateValue) {
            return ['score' => 0.0, 'reason' => null];
        }

        if ($currentValue === $candidateValue) {
            return ['score' => 1.0, 'reason' => $exactReason];
        }

        $currentIndex = array_search($currentValue, $ordering, true);
        $candidateIndex = array_search($candidateValue, $ordering, true);

        if ($currentIndex === false || $candidateIndex === false) {
            return ['score' => 0.0, 'reason' => null];
        }

        if (abs($currentIndex - $candidateIndex) === 1) {
            return ['score' => 0.5, 'reason' => $partialReason];
        }

        return ['score' => 0.0, 'reason' => null];
    }

    /**
     * Age compatibility:
     * - full points when ranges overlap
     * - half points when ranges are close
     * - zero otherwise
     *
     * @return array{score: float, reason: ?string}
     */
    private function evaluateAgeMatch(
        ?int $currentMin,
        ?int $currentMax,
        ?int $candidateMin,
        ?int $candidateMax
    ): array {
        if ($currentMin === null || $currentMax === null || $candidateMin === null || $candidateMax === null) {
            return ['score' => 0.0, 'reason' => null];
        }

        if ($currentMin <= $candidateMax && $candidateMin <= $currentMax) {
            return ['score' => 1.0, 'reason' => 'Age ranges overlap'];
        }

        $gap = $currentMin > $candidateMax
            ? $currentMin - $candidateMax
            : $candidateMin - $currentMax;

        if ($gap <= 5) {
            return ['score' => 0.5, 'reason' => 'Age ranges are close'];
        }

        return ['score' => 0.0, 'reason' => null];
    }

    /**
     * Interests compatibility:
     * - full points if interests lists match
     * - half points if there is any overlap
     * - zero if there is no overlap
     *
     * @return array{score: float, reason: ?string}
     */
    private function evaluateInterestsMatch(mixed $currentInterests, mixed $candidateInterests): array
    {
        $currentList = $this->normalizeInterestList($currentInterests);
        $candidateList = $this->normalizeInterestList($candidateInterests);

        if (empty($currentList) || empty($candidateList)) {
            return ['score' => 0.0, 'reason' => null];
        }

        $shared = array_values(array_intersect($currentList, $candidateList));

        if (empty($shared)) {
            return ['score' => 0.0, 'reason' => null];
        }

        sort($currentList);
        sort($candidateList);

        if ($currentList === $candidateList) {
            return ['score' => 1.0, 'reason' => 'Interests match'];
        }

        return [
            'score' => 0.5,
            'reason' => 'Shared interests: ' . implode(', ', array_slice($shared, 0, 3)),
        ];
    }

    private function normalizeText(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9\s]+/i', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return trim($value);
    }

    private function normalizePreferenceGender(mixed $value): string
    {
        $value = strtolower(trim((string) $value));

        return match ($value) {
            'male', 'female', 'any' => $value,
            default => 'any',
        };
    }

    private function normalizeUserGender(mixed $value): string
    {
        $value = strtolower(trim((string) $value));

        return match ($value) {
            'male', 'female', 'other' => $value,
            default => $value === '' ? 'other' : $value,
        };
    }

    private function normalizePreferenceValue(string $type, mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strtolower(trim((string) $value));

        return match ($type) {
            'cleanliness' => match ($value) {
                '1', 'very_clean' => 'very_clean',
                '2', 'clean' => 'clean',
                '3', 'moderate' => 'moderate',
                '4', '5', 'relaxed' => 'relaxed',
                default => $value,
            },
            'sleep' => match ($value) {
                'early', 'early_bird' => 'early_bird',
                'late', 'night_owl' => 'night_owl',
                'flexible' => 'flexible',
                default => $value,
            },
            'study' => match ($value) {
                'quiet' => 'quiet',
                'group', 'social' => 'social',
                'both', 'moderate' => 'moderate',
                default => $value,
            },
            'smoking' => match ($value) {
                'no' => 'no',
                'outside_only', 'neutral' => 'outside_only',
                'yes' => 'yes',
                default => $value,
            },
            'alcohol' => match ($value) {
                'no' => 'no',
                'occasionally', 'neutral' => 'occasionally',
                'yes' => 'yes',
                default => $value,
            },
            default => $value,
        };
    }

    /**
     * @return array<int, string>
     */
    private function tokenizeLocation(string $value): array
    {
        $tokens = preg_split('/\s+/', $value) ?: [];
        $tokens = array_filter($tokens, static fn (string $token): bool => strlen($token) > 2);

        return array_values(array_unique($tokens));
    }

    /**
     * @return array<int, string>
     */
    private function normalizeInterestList(mixed $value): array
    {
        if (is_array($value)) {
            $parts = $value;
        } else {
            $parts = explode(',', (string) $value);
        }

        $parts = array_map(
            static fn ($item): string => strtolower(trim((string) $item)),
            $parts
        );

        $parts = array_filter($parts, static fn (string $item): bool => $item !== '');

        return array_values(array_unique($parts));
    }
}
