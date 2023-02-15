<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Game;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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
Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('/signup', [AuthController::class, 'register']);
        Route::post('/signin', [AuthController::class, 'login']);
    });

    Route::get('/users/{username}', [UserController::class, 'getUsers']);
});

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::prefix('v1')->group(function(){

        Route::prefix('auth')->group(function() {
            Route::post('/signout', [AuthController::class, 'logout']);
    
        });

        Route::post('/games/{slug}/upload', [GameController::class, 'file']);
        Route::get('/games', [GameController::class, 'index']);
        Route::post('/games', [GameController::class, 'store']);
        Route::get('/games/{slug}', [GameController::class, 'show']);
        Route::put('/games/{slug}', [GameController::class, 'update']);
        Route::delete('/games/{slug}', [GameController::class, 'destroy']);
    });
});
