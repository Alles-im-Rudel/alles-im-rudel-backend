<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Discord\DiscordMassege;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileIndexRequest;
use App\Http\Requests\Auth\ProfileMainSummonerRequest;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Branch;
use App\Models\BranchUserMemberShip;
use App\Models\Summoner;
use App\Models\User;
use App\Services\Images\ImageGenerator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function DeepCopy\deep_copy;


class ProfileController extends Controller
{
	/**
	 * @param  ProfileIndexRequest  $request
	 * @return UserResource
	 */
	public function index(ProfileIndexRequest $request): UserResource
	{
		$userId = Auth::id();

		return new UserResource(User::with([
			'summoners',
			'userGroups',
			'mainSummoner',
			'image',
			'thumbnail',
			'branchUserMemberShips',
			'branchUserMemberShips.branch'
		])->find($userId));
	}

	/**
	 * @param  \App\Http\Requests\Auth\ProfileUpdateRequest  $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(ProfileUpdateRequest $request): JsonResponse
	{
		/** @var User $user */
		$user = Auth::user();
		$originalUser = deep_copy($user);

		$user->first_name = $request->firstName;
		$user->last_name = $request->lastName;
		$user->email = $request->email;
		$user->salutation = $request->salutation;
		$user->phone = $request->phone;

		if ($request->password && $request->passwordRepeat) {
			if ($request->password !== $request->passwordRepeat) {
				return response()->json([
					"message" => "Die Passwörter stimmen nicht überein."
				], Response::HTTP_UNPROCESSABLE_ENTITY);
			}
			$user->password = Hash::make($request->password);
		}

		$user->save();

		if ($originalUser->email !== $user->email) {
			$user->wants_email_notification = false;
			$user->email_verified_at = null;
			$user->save();
			$user->sendEmailVerificationNotification();
		}

		return response()->json([
			'message' => 'Das Profil wurde erfolgreich bearbeitet.',
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Models\BranchUserMemberShip  $branchUserMemberShip
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function leaveBranch(BranchUserMemberShip $branchUserMemberShip): JsonResponse
	{
		/** @var User $user */
		$user = Auth::user();

		$date = Carbon::now()->addMonth()->endOfMonth()->format('d.m.Y');
		$branchUserMemberShip->loadMissing('branch');
		$branchUserMemberShip->wants_to_leave_at = now();
		$branchUserMemberShip->exported_at = null;
		$branchUserMemberShip->save();

		if ($branchUserMemberShip->branch_id !== 1) {
			$message = new DiscordMassege('Spartenaustritt', $user->full_name." möchte die Sparte ". $branchUserMemberShip->branch->name ." verlassen.");
			$message->sendMessage();

			return response()->json([
				'message' => 'Der Spartenaustrittsantrag ist erfolgreich eingegangen und wird am '.$date.' wirksam.',
			], Response::HTTP_OK);
		}

		$message = new DiscordMassege('Vereinsaustritt', $user->full_name." möchte den Verein verlassen.");
		$message->sendMessage();

		return response()->json([
			'message' => 'Der Vereinsaustrittsantrag ist erfolgreich eingegangen und wird am '.$date.' wirksam.',
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Models\Branch  $branch
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function joinBranch(Branch $branch): JsonResponse
	{
		/** @var User $user */
		$user = Auth::user();

		BranchUserMemberShip::create([
			'user_id'   => $user->id,
			'branch_id' => $branch->id
		]);

		$message = new DiscordMassege('Neuer Spartenantrag', $user->full_name." möchte ". $branch->name ." beitreten.");
		$message->sendMessage();

		return response()->json([
			'message' => 'Der Spartenbeitrittsantrag ist erfolgreich eingegangen.',
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Models\BranchUserMemberShip  $branchUserMemberShip
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function cancelBranch(BranchUserMemberShip $branchUserMemberShip): JsonResponse
	{
		/** @var User $user */
		$user = Auth::user();

		$branchUserMemberShip->loadMissing('branch');

		if ($branchUserMemberShip->branch_id !== 1) {
			$message = new DiscordMassege('Spartenaustritt zurückgezogen', $user->full_name." möchte die Sparte ". $branchUserMemberShip->branch->name ." doch nicht verlassen.");
			$message->sendMessage();

			if ($branchUserMemberShip->wants_to_leave) {
				$branchUserMemberShip->wants_to_leave_at = null;
				$branchUserMemberShip->save();



				return response()->json([
					'message' => 'Der Spartenaustrittsantrag wurde erfolgreich zurückgezogen.',
				], Response::HTTP_OK);
			}

			$branchUserMemberShip->delete();
			return response()->json([
				'message' => 'Der Spartenbeitrittsantrag wurde erfolgreich zurückgezogen.',
			], Response::HTTP_OK);
		}

		$branchUserMemberShip->wants_to_leave_at = null;
		$branchUserMemberShip->exported_at = now();
		$branchUserMemberShip->save();

		$message = new DiscordMassege('Vereinsaustritt zurückgezogen', $user->full_name." möchte den Verein doch nicht verlassen.");
		$message->sendMessage();

		return response()->json([
			'message' => 'Der Vereinsaustritt wurde erfolgreich zurückgezogen.',
		], Response::HTTP_OK);
	}

	public function mainSummoner(ProfileMainSummonerRequest $request)
	{
		$userId = Auth::id();

		if (!$userId) {
			return response(null, Response::HTTP_UNAUTHORIZED);
		}

		Summoner::where('main_user_id', $userId)->update([
			'main_user_id' => null
		]);

		if ($request->mainSummonerId) {
			Summoner::find($request->mainSummonerId)->update([
				'main_user_id' => $userId
			]);
		}

		return response()->json([
			'message' => 'Das Profil wurde erfolgreich bearbeitet.',
		], Response::HTTP_OK);
	}

	//todo: implement profile delete
	public function delete()
	{

	}

	/**
	 * @param  Request  $request
	 * @return \App\Http\Resources\UserResource
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function updateImage(Request $request): UserResource
	{
		$this->validate($request, [
			'image' => 'required|file|mimes:jpg,jpeg,png'
		]);

		/** @var User $user */
		$user = Auth::user();

		$originalFileName = optional($request->file('image'))->getClientOriginalName();
		$image = ImageGenerator::resizeImageIfNeeded($request->file('image'));
		$thumbnail = ImageGenerator::createThumbnail($request->file('image'));

		$user->image()->delete();
		$user->image()->create([
			'image'          => $image->encode('data-url'),
			'thumbnail'      => $thumbnail->encode('data-url'),
			'file_name'      => $originalFileName,
			'file_mime_type' => $image->mime(),
			'file_size'      => $image->filesize()
		]);

		return new UserResource($user);
	}
}
