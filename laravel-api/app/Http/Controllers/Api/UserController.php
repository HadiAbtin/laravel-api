<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function index(): JsonResponse
    {
        $users = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'role' => 'admin',
                'created_at' => '2024-01-15T10:30:00Z'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'role' => 'user',
                'created_at' => '2024-01-16T14:20:00Z'
            ],
            [
                'id' => 3,
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'role' => 'moderator',
                'created_at' => '2024-01-17T09:15:00Z'
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users,
            'total' => count($users),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get user by ID
     */
    public function show($id): JsonResponse
    {
        // Convert string to int if needed
        $id = (int) $id;
        $users = [
            1 => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'role' => 'admin',
                'profile' => [
                    'bio' => 'System Administrator',
                    'avatar' => 'https://via.placeholder.com/150',
                    'last_login' => '2024-01-20T08:45:00Z'
                ],
                'created_at' => '2024-01-15T10:30:00Z'
            ],
            2 => [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'role' => 'user',
                'profile' => [
                    'bio' => 'Regular User',
                    'avatar' => 'https://via.placeholder.com/150',
                    'last_login' => '2024-01-19T16:20:00Z'
                ],
                'created_at' => '2024-01-16T14:20:00Z'
            ],
            3 => [
                'id' => 3,
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'role' => 'moderator',
                'profile' => [
                    'bio' => 'Content Moderator',
                    'avatar' => 'https://via.placeholder.com/150',
                    'last_login' => '2024-01-18T11:30:00Z'
                ],
                'created_at' => '2024-01-17T09:15:00Z'
            ]
        ];

        if (!isset($users[$id])) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error' => 'USER_NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $users[$id],
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
            'role' => 'required|in:admin,user,moderator'
        ]);

        // Simulate user creation
        $newUser = [
            'id' => rand(100, 999),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'created_at' => now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $newUser,
            'timestamp' => now()->toISOString()
        ], 201);
    }

    /**
     * Update user
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Convert string to int if needed
        $id = (int) $id;
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email',
            'role' => 'sometimes|in:admin,user,moderator'
        ]);

        // Simulate user update
        $updatedUser = [
            'id' => $id,
            'name' => $validated['name'] ?? 'Updated User',
            'email' => $validated['email'] ?? 'updated@example.com',
            'role' => $validated['role'] ?? 'user',
            'updated_at' => now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $updatedUser,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Delete user
     */
    public function destroy($id): JsonResponse
    {
        // Convert string to int if needed
        $id = (int) $id;
        // Simulate user deletion
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
            'data' => ['id' => $id],
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get user statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_users' => 3,
            'active_users' => 2,
            'admins' => 1,
            'moderators' => 1,
            'regular_users' => 1,
            'last_30_days_registrations' => 2,
            'average_session_duration' => '2h 15m'
        ];

        return response()->json([
            'success' => true,
            'message' => 'User statistics retrieved successfully',
            'data' => $stats,
            'timestamp' => now()->toISOString()
        ]);
    }
}
