<?php

use Illuminate\Support\Facades\Route;

Route::get('ping', 'Api\PingController@index');

// Test routes for CI/CD verification
Route::get('test', 'Api\TestController@index');
Route::get('test/health', 'Api\TestController@health');
Route::get('test/version', 'Api\TestController@version');

// User Management API Routes (New Feature)
Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'Api\UserController@index');
    Route::get('/stats', 'Api\UserController@stats');
    Route::get('/{id}', 'Api\UserController@show');
    Route::post('/', 'Api\UserController@store');
    Route::put('/{id}', 'Api\UserController@update');
    Route::delete('/{id}', 'Api\UserController@destroy');
});

// Workflow test routes
Route::get('workflow/test', 'Api\WorkflowTestController@test');
Route::get('workflow/status', 'Api\WorkflowTestController@status');
Route::get('workflow/deployment', 'Api\WorkflowTestController@deployment');

// Web test routes for comprehensive testing
Route::get('web-test/dashboard', 'Api\WebTestController@dashboard');
Route::get('web-test/database', 'Api\WebTestController@databaseTest');
Route::get('web-test/redis', 'Api\WebTestController@redisTest');
Route::get('web-test/cache', 'Api\WebTestController@cacheTest');
Route::get('web-test/performance', 'Api\WebTestController@performanceTest');
Route::get('web-test/log', 'Api\WebTestController@logTest');
Route::get('web-test/environment', 'Api\WebTestController@environmentTest');

Route::get('assets/{uuid}/render', 'Api\Assets\RenderFileController@show');

// Performance Test Routes (Public for testing)
Route::get('performance/without-cache', 'Api\PerformanceTestController@testWithoutCache');
Route::get('performance/with-cache', 'Api\PerformanceTestController@testWithCache');
Route::get('performance/cache-operations', 'Api\PerformanceTestController@testCacheOperations');
Route::get('performance/cache-stats', 'Api\PerformanceTestController@getCacheStats');
Route::post('performance/clear-cache', 'Api\PerformanceTestController@clearCache');

Route::post('register', 'Api\Auth\RegisterController@store');
Route::post('passwords/reset', 'Api\Auth\PasswordsController@store');
Route::put('passwords/reset', 'Api\Auth\PasswordsController@update');

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'Api\Users\UsersController@index');
        Route::post('/', 'Api\Users\UsersController@store');
        Route::get('/{uuid}', 'Api\Users\UsersController@show');
        Route::put('/{uuid}', 'Api\Users\UsersController@update');
        Route::patch('/{uuid}', 'Api\Users\UsersController@update');
        Route::delete('/{uuid}', 'Api\Users\UsersController@destroy');
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'Api\Users\RolesController@index');
        Route::post('/', 'Api\Users\RolesController@store');
        Route::get('/{uuid}', 'Api\Users\RolesController@show');
        Route::put('/{uuid}', 'Api\Users\RolesController@update');
        Route::patch('/{uuid}', 'Api\Users\RolesController@update');
        Route::delete('/{uuid}', 'Api\Users\RolesController@destroy');
    });

    Route::get('permissions', 'Api\Users\PermissionsController@index');

    Route::group(['prefix' => 'me'], function () {
        Route::get('/', 'Api\Users\ProfileController@index');
        Route::put('/', 'Api\Users\ProfileController@update');
        Route::patch('/', 'Api\Users\ProfileController@update');
        Route::put('/password', 'Api\Users\ProfileController@updatePassword');
    });

    Route::group(['prefix' => 'assets'], function () {
        Route::post('/', 'Api\Assets\UploadFileController@store');
    });
});
