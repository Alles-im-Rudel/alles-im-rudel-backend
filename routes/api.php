<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Level\LevelController;
use App\Http\Controllers\Lol\ClashController;
use App\Http\Controllers\Lol\ClashMemberPickerController;
use App\Http\Controllers\Lol\LolApiController;
use App\Http\Controllers\Lol\SummonerController;
use App\Http\Controllers\Lol\SummonerPickerController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserPickerController;
use App\Http\Controllers\UserGroup\UserGroupController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('auth', [AuthController::class, 'index']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['prefix' => 'clash'], static function () {
	Route::get('', [ClashController::class, 'index']);
});

Route::group(['middleware' => ['auth:api']], static function () {

	Route::group(['prefix' => 'levels'], static function () {
		Route::get('', [LevelController::class, 'index']);
	});

	Route::group(['prefix' => 'users'], static function () {
		Route::get('', [UserController::class, 'index']);
		Route::get('/picker', [UserPickerController::class, 'index']);
		Route::put('sync-permissions/{user}', [UserController::class, 'syncPermissions']);
		Route::put('sync-user-groups/{user}', [UserController::class, 'syncUserGroups']);
		Route::get('{user}', [UserController::class, 'show']);
		Route::put('{user}', [UserController::class, 'update']);
		Route::delete('{user}', [UserController::class, 'delete']);
	});
	Route::group(['prefix' => 'permissions'], static function () {
		Route::get('', [PermissionController::class, 'index']);
	});
	Route::group(['prefix' => 'user-groups'], static function () {
		Route::get('', [UserGroupController::class, 'index']);
	});
	Route::group(['prefix' => 'summoners'], static function () {
		Route::get('', [SummonerController::class, 'index']);
		Route::get('show', [SummonerController::class, 'show']);
		Route::get('picker', [SummonerPickerController::class, 'index']);
		Route::get('reload/{summoner}', [SummonerController::class, 'reload']);
		Route::put('detach-main-user/{summoner}', [SummonerController::class, 'detachMainUser']);
		Route::put('attach-main-user/{summoner}', [SummonerController::class, 'attachMainUser']);
	});
	Route::group(['prefix' => 'lol'], static function () {
		Route::get('', [LolApiController::class, 'index']);
	});
	Route::group(['prefix' => 'clash'], static function () {
		Route::post('', [ClashController::class, 'create']);
		Route::get('member-picker', [ClashMemberPickerController::class, 'index']);
		Route::put('{clashTeam}', [ClashController::class, 'update']);
		Route::delete('{clashTeam}', [ClashController::class, 'delete']);
	});
});