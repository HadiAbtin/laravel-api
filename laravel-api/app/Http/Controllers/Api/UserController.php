<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function index(): JsonResponse
    {
        $users = User::with('roles')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users,
            'total' => $users->count(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get user by UUID
     */
    public function show($uuid): JsonResponse
    {
        $user = User::where('uuid', $uuid)->with('roles')->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error' => 'USER_NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $user,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Create new user
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'uuid' => Str::uuid(),
        ]);

        $user->load('roles');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
            'timestamp' => now()->toISOString()
        ], 201);
    }

    /**
     * Update user
     */
    public function update(Request $request, $uuid): JsonResponse
    {
        $user = User::where('uuid', $uuid)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error' => 'USER_NOT_FOUND'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);
        $user->load('roles');

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Delete user
     */
    public function destroy($uuid): JsonResponse
    {
        $user = User::where('uuid', $uuid)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error' => 'USER_NOT_FOUND'
            ], 404);
        }

        $user->delete();

        return response()->json(null, 204);
    }

    /**
     * Get user statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('email_verified_at', '!=', null)->count(),
            'admins' => User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->count(),
            'moderators' => User::whereHas('roles', function($q) {
                $q->where('name', 'moderator');
            })->count(),
            'regular_users' => User::whereHas('roles', function($q) {
                $q->where('name', 'user');
            })->count(),
            'last_30_days_registrations' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'User statistics retrieved successfully',
            'data' => $stats,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get environment info for testing
     */
    public function envInfo(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Environment info retrieved successfully',
            'data' => [
                'environment' => app()->environment(),
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'version' => '1.0.0-dev-test',
                'timestamp' => now()->toISOString()
            ]
        ]);
    }
}
