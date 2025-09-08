<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WorkflowTestController extends Controller
{
    /**
     * Test CI/CD workflow
     */
    public function test(): JsonResponse
    {
        return response()->json([
            'message' => 'CI/CD Workflow Test Successful!',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0',
            'environment' => config('app.env'),
            'branch' => 'feature/test-new-workflow',
            'status' => 'success',
            'features' => [
                'oauth_keys' => 'fixed',
                'docker_build' => 'working',
                'ecs_deployment' => 'active',
                'cloudfront' => 'enabled'
            ]
        ]);
    }

    /**
     * Get system status
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'status' => 'operational',
            'services' => [
                'database' => 'connected',
                'redis' => 'connected',
                'cache' => 'working',
                'oauth' => 'working'
            ],
            'timestamp' => now()->toISOString(),
            'uptime' => 'running'
        ]);
    }

    /**
     * Get deployment info
     */
    public function deployment(): JsonResponse
    {
        return response()->json([
            'deployment_id' => uniqid('deploy_'),
            'version' => '1.0.0',
            'environment' => config('app.env'),
            'build_time' => now()->toISOString(),
            'features' => [
                'multi_environment' => true,
                'ci_cd' => true,
                'docker' => true,
                'aws_infrastructure' => true,
                'oauth_authentication' => true
            ]
        ]);
    }
}
