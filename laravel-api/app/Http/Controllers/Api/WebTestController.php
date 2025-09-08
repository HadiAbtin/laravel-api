<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebTestController extends Controller
{
    /**
     * Dashboard endpoint - نمایش اطلاعات کلی سیستم
     */
    public function dashboard()
    {
        $systemInfo = [
            'server' => [
                'name' => config('app.name'),
                'version' => '1.0.0',
                'environment' => app()->environment(),
                'debug_mode' => config('app.debug'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
            ],
            'php' => [
                'version' => PHP_VERSION,
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
            ],
            'laravel' => [
                'version' => app()->version(),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_driver' => config('queue.default'),
            ],
            'database' => [
                'connection' => config('database.default'),
                'driver' => config('database.connections.mysql.driver'),
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => config('database.connections.mysql.database'),
            ],
            'redis' => [
                'host' => config('database.redis.default.host'),
                'port' => config('database.redis.default.port'),
                'database' => config('database.redis.default.database'),
            ],
            'timestamp' => now()->toISOString(),
        ];

        return response()->json([
            'title' => 'Laravel API System Dashboard',
            'status' => 'operational',
            'data' => $systemInfo
        ]);
    }

    /**
     * Database test endpoint
     */
    public function databaseTest()
    {
        try {
            // Test database connection
            $connectionTest = DB::connection()->getPdo();
            
            // Test query execution
            $queryTest = DB::select('SELECT 1 as test, NOW() as current_time');
            
            // Test table existence
            $tables = DB::select('SHOW TABLES');
            $tableNames = array_map(function($table) {
                return array_values((array)$table)[0];
            }, $tables);

            return response()->json([
                'status' => 'success',
                'message' => 'Database connection successful',
                'data' => [
                    'connection' => 'MySQL',
                    'query_test' => $queryTest[0],
                    'tables_count' => count($tableNames),
                    'tables' => $tableNames,
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Redis test endpoint
     */
    public function redisTest()
    {
        try {
            // Test Redis connection
            $redis = Redis::connection();
            $ping = $redis->ping();
            
            // Test Redis operations
            $testKey = 'test_key_' . time();
            $testValue = 'test_value_' . rand(1000, 9999);
            
            $redis->set($testKey, $testValue, 'EX', 60); // Expire in 60 seconds
            $retrievedValue = $redis->get($testKey);
            $redis->del($testKey);
            
            // Get Redis info
            $info = $redis->info();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Redis connection successful',
                'data' => [
                    'ping' => $ping,
                    'test_operation' => [
                        'set_key' => $testKey,
                        'set_value' => $testValue,
                        'retrieved_value' => $retrievedValue,
                        'operation_successful' => $testValue === $retrievedValue
                    ],
                    'redis_info' => [
                        'version' => $info['redis_version'] ?? 'unknown',
                        'uptime' => $info['uptime_in_seconds'] ?? 'unknown',
                        'connected_clients' => $info['connected_clients'] ?? 'unknown',
                        'used_memory' => $info['used_memory_human'] ?? 'unknown'
                    ],
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Redis connection failed',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Cache test endpoint
     */
    public function cacheTest()
    {
        try {
            $testKey = 'cache_test_' . time();
            $testValue = [
                'message' => 'Cache test successful',
                'timestamp' => now()->toISOString(),
                'random_number' => rand(1000, 9999)
            ];
            
            // Test cache operations
            Cache::put($testKey, $testValue, 60); // Cache for 60 seconds
            $retrievedValue = Cache::get($testKey);
            $cacheExists = Cache::has($testKey);
            Cache::forget($testKey);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Cache operations successful',
                'data' => [
                    'cache_driver' => config('cache.default'),
                    'test_operation' => [
                        'key' => $testKey,
                        'value' => $testValue,
                        'retrieved_value' => $retrievedValue,
                        'cache_exists' => $cacheExists,
                        'operation_successful' => $testValue === $retrievedValue
                    ],
                    'timestamp' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cache operations failed',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Performance test endpoint
     */
    public function performanceTest()
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        // Simulate some work
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data[] = [
                'id' => $i,
                'value' => rand(1, 100),
                'timestamp' => now()->toISOString()
            ];
        }
        
        // Test database query performance
        $dbStartTime = microtime(true);
        $users = DB::table('users')->count();
        $dbEndTime = microtime(true);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Performance test completed',
            'data' => [
                'execution_time' => [
                    'total_time_ms' => round(($endTime - $startTime) * 1000, 2),
                    'database_query_time_ms' => round(($dbEndTime - $dbStartTime) * 1000, 2)
                ],
                'memory_usage' => [
                    'start_memory_mb' => round($startMemory / 1024 / 1024, 2),
                    'end_memory_mb' => round($endMemory / 1024 / 1024, 2),
                    'memory_used_mb' => round(($endMemory - $startMemory) / 1024 / 1024, 2)
                ],
                'test_data' => [
                    'array_size' => count($data),
                    'users_count' => $users
                ],
                'timestamp' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Log test endpoint
     */
    public function logTest()
    {
        $logMessage = 'Web Test Log - ' . now()->toISOString();
        $logData = [
            'test_type' => 'web_test',
            'timestamp' => now()->toISOString(),
            'random_data' => rand(1000, 9999),
            'user_agent' => request()->header('User-Agent'),
            'ip_address' => request()->ip()
        ];
        
        // Test different log levels
        Log::info($logMessage, $logData);
        Log::warning('Web Test Warning', $logData);
        Log::error('Web Test Error (simulated)', $logData);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Log test completed',
            'data' => [
                'log_message' => $logMessage,
                'log_data' => $logData,
                'log_levels_tested' => ['info', 'warning', 'error'],
                'timestamp' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Environment test endpoint
     */
    public function environmentTest()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Environment test completed',
            'data' => [
                'environment' => app()->environment(),
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'app_debug' => config('app.debug'),
                'app_timezone' => config('app.timezone'),
                'app_locale' => config('app.locale'),
                'database_connection' => config('database.default'),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_driver' => config('queue.default'),
                'mail_driver' => config('mail.default'),
                'broadcast_driver' => config('broadcasting.default'),
                'server_info' => [
                    'php_version' => PHP_VERSION,
                    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
                    'server_name' => $_SERVER['SERVER_NAME'] ?? 'unknown',
                    'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
                    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
                ],
                'timestamp' => now()->toISOString()
            ]
        ]);
    }
}
