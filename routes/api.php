<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/auth', [AuthController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:api']], static function () {

Route::group(['prefix' => '/users'], static function () {
	Route::get('', [UserController::class, 'index']);
});
});