<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function createRole()
    {
        Role::create(['name' => 'coach']);
        Role::create(['name' => 'client']);

        return 'Done';
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if(!empty($request->register_as) && $request->register_as == 'coach') {
            $user->assignRole('coach');
        } else {
            $user->assignRole('client');
        }

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'User registered successfully.']
                ],
                'data' => []
            ],
            201
        );
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
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

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('CoachingApp', ['*'], Carbon::now()->addHour())->plainTextToken;
        // $token->accessToken->expires_at = Carbon::now()->addHour();
        // $token->accessToken->save();
        // $token = $token->plainTextToken;

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Logged In successfully.']
                ],
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ],
            200
        );
    }

    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Logged out successfully.']
                ],
                'data' => []
            ],
            200
        );
    }
}
