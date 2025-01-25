<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CoachingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoachingSessionController extends Controller
{
    public function createCoachingSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coach_id' => 'required|integer',
            'client_id' => 'required|integer',
            'title' => 'required|string',
            'session_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => $validator->errors()->all()[0]]
                    ],
                    'data' => []
                ],
                400
            );
        }

        $coachingSession = CoachingSession::create($validator->validated() + ['status' => 'Pending']);

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Coaching Session created successfully.']
                ],
                'data' => [
                    'coaching_session' => $coachingSession
                ]
            ],
            201
        );
    }

    public function getAllCoachingSessions()
    {
        $user = Auth::user();

        $coachingSessions = CoachingSession::query();
        if ($user->hasRole('coach')) {
            $coachingSessions->where('coach_id', $user->id);
        } else if ($user->hasRole('client')) {
            $coachingSessions->where('client_id', $user->id);
        }

        $coachingSessions = $coachingSessions->with('coach', 'client')->get();

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'All Coaching Sessions.']
                ],
                'data' => [
                    'coaching_sessions' => $coachingSessions
                ]
            ],
            200
        );
    }

    public function getCoachingSession(Request $request)
    {
        $user = Auth::user();

        $coachingSession = CoachingSession::query();
        if ($user->hasRole('coach')) {
            $coachingSession->where('coach_id', $user->id);
        } else if ($user->hasRole('client')) {
            $coachingSession->where('client_id', $user->id);
        }

        $coachingSession = $coachingSession->where('id', $request->id)->with('coach', 'client')->get();

        if (empty($coachingSession)) {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => 'Coaching Session not found.']
                    ],
                    'data' => []
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Coaching Session.']
                ],
                'data' => [
                    'coaching_session' => $coachingSession
                ]
            ],
            200
        );
    }

    public function updateCoachingSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coach_id' => 'required|integer',
            'client_id' => 'required|integer',
            'title' => 'required|string',
            'session_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => $validator->errors()->all()[0]]
                    ],
                    'data' => []
                ],
                400
            );
        }

        $coachingSession = CoachingSession::find($request->id);

        if (empty($coachingSession)) {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => 'Coaching Session not found.']
                    ],
                    'data' => []
                ],
                404
            );
        }

        if ($coachingSession->status == 'Completed') {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => 'Cannot update a completed session.']
                    ],
                    'data' => []
                ],
                400
            );
        }

        $coachingSession = $coachingSession->update($validator->validated());

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Coaching Session updated successfully.']
                ],
                'data' => [
                    'coaching_session' => $coachingSession
                ]
            ],
            200
        );
    }

    public function deleteCoachingSession(Request $request)
    {
        $coachingSession = CoachingSession::find($request->id);

        if (empty($coachingSession)) {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => 'Coaching Session not found.']
                    ],
                    'data' => []
                ],
                404
            );
        }

        $coachingSession->delete();

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Coaching Session deleted successfully.']
                ],
                'data' => []
            ],
            200
        );
    }

    public function getUncompletedCoachingSessions()
    {
        $user = Auth::user();

        $coachingSessions = CoachingSession::query();
        if ($user->hasRole('coach')) {
            $coachingSessions->where('coach_id', $user->id);
        } else if ($user->hasRole('client')) {
            $coachingSessions->where('client_id', $user->id);
        }

        $coachingSessions = $coachingSessions->where('status', 'Pending')->get();

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Uncompleted Coaching Sessions.']
                ],
                'data' => [
                    'coaching_sessions' => $coachingSessions
                ]
            ],
            200
        );
    }

    public function getCompletedCoachingSessions()
    {
        $user = Auth::user();

        $coachingSessions = CoachingSession::query();
        if ($user->hasRole('coach')) {
            $coachingSessions->where('coach_id', $user->id);
        } else if ($user->hasRole('client')) {
            $coachingSessions->where('client_id', $user->id);
        }

        $coachingSessions = $coachingSessions->where('status', 'Completed')->get();

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Completed Coaching Sessions.']
                ],
                'data' => [
                    'coaching_sessions' => $coachingSessions
                ]
            ],
            200
        );
    }

    public function markCoachingSessionCompleted(Request $request)
    {
        $coachingSession = CoachingSession::find($request->id);

        if ($coachingSession && $coachingSession->status == 'pending') {
            $coachingSession->status = 'completed';
            $coachingSession->save();
        }

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Coaching Session marked as completed.']
                ],
                'data' => []
            ],
            200
        );
    }
}
