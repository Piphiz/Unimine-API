<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/url', [UrlController::class, 'index']);
Route::get('/url/{hash}', [UrlController::class, 'show']);
Route::post('/url', [UrlController::class, 'store']);

Route::post('/auth/login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'api.verify.auth',
    'prefix' => '/auth'
], function ($router) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'api.verify.auth'
], function ($route) {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);

    Route::get('/teste', function (Request $request) {
        return response()->json([
            'message' => 'Hello ' . $request->get('uppername')
        ]);
    });
});

