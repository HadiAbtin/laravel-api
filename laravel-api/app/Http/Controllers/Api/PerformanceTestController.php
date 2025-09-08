<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceTestController extends Controller
{
    /**
     * Test database performance without cache
     */
    public function testWithoutCache()
    {
        $startTime = microtime(true);
        
        // Simulate heavy database operations
        $users = User::with('roles')->get();
        $roles = DB::table('roles')->get();
        $permissions = DB::table('permissions')->get();
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        return response()->json([
            'method' => 'Without Cache',
            'execution_time_ms' => round($executionTime, 2),
            'users_count' => $users->count(),
            'roles_count' => $roles->count(),
            'permissions_count' => $permissions->count(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Test database performance with Redis cache
     */
    public function testWithCache()
    {
        $startTime = microtime(true);
        
        // Try to get data from cache first
        $users = Cache::remember('test_users', 60, function () {
            return User::with('roles')->get();
        });
        
        $roles = Cache::remember('test_roles', 60, function () {
            return DB::table('roles')->get();
        });
        
        $permissions = Cache::remember('test_permissions', 60, function () {
            return DB::table('permissions')->get();
        });
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        return response()->json([
            'method' => 'With Redis Cache',
            'execution_time_ms' => round($executionTime, 2),
            'users_count' => $users->count(),
            'roles_count' => $roles->count(),
            'permissions_count' => $permissions->count(),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Test cache operations
     */
    public function testCacheOperations()
    {
        $startTime = microtime(true);
        
        // Test cache write
        Cache::put('test_key', 'test_value', 60);
        
        // Test cache read
        $value = Cache::get('test_key');
        
        // Test cache exists
        $exists = Cache::has('test_key');
        
        // Test cache delete
        Cache::forget('test_key');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        
        return response()->json([
            'method' => 'Cache Operations Test',
            'execution_time_ms' => round($executionTime, 2),
            'cache_value' => $value,
            'cache_exists' => $exists,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Clear all cache
     */
    public function clearCache()
    {
        Cache::flush();
        
        return response()->json([
            'message' => 'Cache cleared successfully',
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats()
    {
        $redis = app('redis');
        
        try {
            $info = $redis->info();
            
            return response()->json([
                'redis_version' => $info['redis_version'] ?? 'Unknown',
                'used_memory' => $info['used_memory_human'] ?? 'Unknown',
                'connected_clients' => $info['connected_clients'] ?? 'Unknown',
                'total_commands_processed' => $info['total_commands_processed'] ?? 'Unknown',
                'keyspace_hits' => $info['keyspace_hits'] ?? 'Unknown',
                'keyspace_misses' => $info['keyspace_misses'] ?? 'Unknown',
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Could not connect to Redis',
                'message' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ]);
        }
    }
}
