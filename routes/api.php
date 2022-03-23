<?php

use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Appointment\AppointmentLikeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Image\ImageController;
use App\Http\Controllers\Level\LevelController;
use App\Http\Controllers\Lol\ClashController;
use App\Http\Controllers\Lol\ClashMemberPickerController;
use App\Http\Controllers\Lol\LolApiController;
use App\Http\Controllers\Lol\SummonerController;
use App\Http\Controllers\Lol\SummonerInfoController;
use App\Http\Controllers\Lol\SummonerPickerController;
use App\Http\Controllers\MemberShip\BranchController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostLikeController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\User\MemberController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserImageController;
use App\Http\Controllers\User\UserPickerController;
use App\Http\Controllers\UserGroup\UserGroupController;
use App\Http\Controllers\VerificationController;
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

Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::group(['prefix' => 'clash'], static function () {
	Route::get('', [ClashController::class, 'index']);
});

Route::group(['prefix' => 'tags'], static function () {
	Route::get('all', [TagController::class, 'all']);
});

Route::group(['prefix' => 'posts'], static function () {
	Route::get('', [PostController::class, 'index']);
	Route::get('{post}', [PostController::class, 'show']);
});

Route::group(['prefix' => 'members'], static function () {
	Route::post('register', [MemberController::class, 'register']);
});

Route::group(['prefix' => 'branches'], static function () {
	Route::get('', [BranchController::class, 'index']);
});

Route::group(['prefix' => 'comments'], static function () {
	Route::get('by/{post}', [CommentController::class, 'byPost']);
});

Route::group(['prefix' => 'profils'], static function () {
	Route::get('', [UserController::class, 'showProfile']);
	Route::get('/check-username/{username}', [UserController::class, 'checkUsername']);
	Route::get('/check-email/{email}', [UserController::class, 'checkEmail']);
});

Route::group(['middleware' => ['auth:api', 'verified']], static function () {

	Route::group(['prefix' => 'members'], static function () {
		Route::get('', [MemberController::class, 'index']);
	});

	Route::group(['prefix' => 'appointments'], static function () {
		Route::get('', [AppointmentController::class, 'index']);
		Route::get('{appointment}', [AppointmentController::class, 'show']);
		Route::post('', [AppointmentController::class, 'store']);
		Route::put('{appointment}', [AppointmentController::class, 'update']);
		Route::delete('{appointment}', [AppointmentController::class, 'delete']);
		Route::get('{appointment}/check', [AppointmentLikeController::class, 'checkLiked']);
		Route::put('{appointment}/change', [AppointmentLikeController::class, 'change']);
	});

	Route::group(['prefix' => 'levels'], static function () {
		Route::get('', [LevelController::class, 'index']);
	});

	Route::group(['prefix' => 'profile'], static function () {
		Route::get('', [ProfileController::class, 'index']);
		Route::put('', [ProfileController::class, 'update']);
		Route::put('main-summoner', [ProfileController::class, 'mainSummoner']);
		Route::delete('', [ProfileController::class, 'delete']);
	});

	Route::group(['prefix' => 'images'], static function () {
		Route::delete('{image}', [ImageController::class, 'delete']);
	});

	Route::group(['prefix' => 'comments'], static function () {
		Route::post('', [CommentController::class, 'store']);
	});

	Route::group(['prefix' => 'posts'], static function () {
		Route::post('', [PostController::class, 'store']);
		Route::put('{post}', [PostController::class, 'update']);
		Route::delete('{post}', [PostController::class, 'delete']);
		Route::post('{post}/image', [PostController::class, 'storeImage']);
		Route::get('{post}/check', [PostLikeController::class, 'checkLiked']);
		Route::put('{post}/change', [PostLikeController::class, 'change']);
	});

	Route::group(['prefix' => 'users'], static function () {
		Route::get('', [UserController::class, 'index']);
		Route::get('all', [UserController::class, 'all']);
		Route::get('picker', [UserPickerController::class, 'index']);
		Route::post('image/{user}', [UserImageController::class, 'store']);
		Route::delete('image/{user}', [UserImageController::class, 'delete']);
		Route::put('sync-permissions/{user}', [UserController::class, 'syncPermissions']);
		Route::put('sync-user-groups/{user}', [UserController::class, 'syncUserGroups']);
		Route::post('', [UserController::class, 'store']);
		Route::get('{user}', [UserController::class, 'show']);
		Route::put('{user}', [UserController::class, 'update']);
		Route::delete('{user}', [UserController::class, 'delete']);
	});
	Route::group(['prefix' => 'permissions'], static function () {
		Route::get('', [PermissionController::class, 'index']);
	});
	Route::group(['prefix' => 'user-groups'], static function () {
		Route::get('', [UserGroupController::class, 'index']);
		Route::get('all', [UserGroupController::class, 'all']);
		Route::get('{userGroup}', [UserGroupController::class, 'show']);
		Route::put('sync-permissions/{userGroup}', [UserGroupController::class, 'syncPermissions']);
		Route::put('sync-users/{userGroup}', [UserGroupController::class, 'syncUsers']);
		Route::put('{userGroup}', [UserGroupController::class, 'update']);
		Route::delete('{userGroup}', [UserGroupController::class, 'delete']);
	});
	Route::group(['prefix' => 'summoners'], static function () {
		Route::get('', [SummonerController::class, 'index']);
		Route::get('show', [SummonerController::class, 'show']);
		Route::get('active-game/{summoner}', [SummonerController::class, 'activeGame']);
		Route::get('info-champions/{summoner}', [SummonerInfoController::class, 'champions']);
		Route::get('info-matches/{summoner}', [SummonerInfoController::class, 'matches']);
		Route::get('info/{summoner}', [SummonerInfoController::class, 'index']);
		Route::get('check-active-game/{summoner}', [SummonerController::class, 'checkActiveGame']);
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
