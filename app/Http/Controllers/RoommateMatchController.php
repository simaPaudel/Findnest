<?php

namespace App\Http\Controllers;

use App\Services\RoommateMatchingService;
use Illuminate\Support\Facades\Auth;

class RoommateMatchController extends Controller
{
    /**
     * Service for roommate matching
     *
     * @var RoommateMatchingService
     */
    protected $matchingService;

    /**
     * Constructor
     *
     * @param RoommateMatchingService $matchingService
     */
    public function __construct(RoommateMatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Get roommate matches for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMatches()
    {
        try {
            $userId = Auth::id();

            // Get matches from the service
            $matches = $this->matchingService->getRoommateMatches($userId);

            return response()->json([
                'success' => true,
                'data' => $matches,
                'count' => count($matches),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching matches',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
