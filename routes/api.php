<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorsController;
use App\Http\Controllers\Api\BooksController;
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

Route::prefix('auth')->group(function () {
    Route::post('sign-up', [AuthController::class, 'signUp']);
    Route::post('sign-in', [AuthController::class, 'signIn']);
});

Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorsController::class, 'index']);
    Route::get('/{id}', [AuthorsController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [AuthorsController::class, 'store']);
        Route::patch('/{id}', [AuthorsController::class, 'update']);
        Route::delete('/{id}', [AuthorsController::class, 'destroy']);
    });
});

Route::prefix('books')->group(function () {
    Route::get('/', [BooksController::class, 'index']);
    Route::get('/{id}', [BooksController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [BooksController::class, 'store']);
        Route::patch('/{id}', [BooksController::class, 'update']);
        Route::delete('/{id}', [BooksController::class, 'destroy']);
    });
});
