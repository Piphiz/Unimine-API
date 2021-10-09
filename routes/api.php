<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\UserController;
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
    'middleware' => 'api.verify.auth',
    'prefix' => '/backoffice'
], function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user/create', [UserController::class, 'store']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::patch('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
});

