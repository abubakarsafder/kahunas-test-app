<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachController extends Controller
{
    public function getCoachAnalytics()
    {
        $coach = User::find(Auth::user()->id);

        $totalSessionsCount = $coach->coachingSessions()->count();
        $completedSessionsCount = $coach->coachingSessions()->where('status', 'Completed')->count();

        $data = [
            'total_sessions_count' => $totalSessionsCount,
            'completed_sessions_percentage' => ($completedSessionsCount / $totalSessionsCount) * 100,
        ];

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Coach Analytics.']
                ],
                'data' => [
                    'analytics' => $data
                ]
            ],
            200
        );
    }
}
