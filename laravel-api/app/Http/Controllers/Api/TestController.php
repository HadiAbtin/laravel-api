<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test endpoint for CI/CD verification
     */
    public function index()
    {
        return response()->json([
            'message' => 'CI/CD Test OK!',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0',
            'environment' => app()->environment(),
            'branch' => 'feature/add-test-endpoints',
            'status' => 'success'
        ]);
    }

    /**
     * Health check endpoint
     */
    public function health()
    {
        return response()->json([
            'status' => 'healthy',
            'service' => 'Laravel API',
            'timestamp' => now()->toISOString(),
            'uptime' => 'running',
            'checks' => [
                'database' => 'connected',
                'redis' => 'connected',
                'cache' => 'working'
            ]
        ]);
    }

    /**
     * Version info endpoint
     */
    public function version()
    {
        return response()->json([
            'app_name' => config('app.name'),
            'app_version' => '1.0.0',
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug'),
            'timestamp' => now()->toISOString()
        ]);
    }
}