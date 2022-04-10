<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\Member\MemberIndexRequest;
use App\Http\Requests\Member\MemberRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Level;
use App\Models\MemberShip;
use App\Models\User;
use App\Services\Images\ImageGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
	/**
	 * @param  MemberIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(MemberIndexRequest $request): AnonymousResourceCollection
	{
		$users = User::with([
			'memberShip', 'memberShip.branches'
		])->whereHas('memberShip', function ($query) {
			return $query->whereNull('activated_at');
		});

		return UserResource::collection($users->paginate(9, '*', $request->page, $request->page));
	}


	/**
	 * @param  \App\Models\User  $user
	 * @return \App\Http\Resources\UserResource
	 */
	public function show(User $user): UserResource
	{
		$user->loadMissing('memberShip', 'memberShip.branches', 'memberShip.country');

		return new UserResource($user);
	}


	/**
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function accept(User $user): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}
		$user->activated_at = now();
		$user->save();

		$user->memberShip->activated_at = now();
		$user->memberShip->save();

		DB::table('branch_member_ship')
			->where('member_ship_id', $user->memberShip->id)
			->update(['activated_at' => now()]);

		return response()->json([
			"message" => "Die Anmeldung wurde erfolgreich bestätigt",
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function reject(User $user): JsonResponse
	{
		if (!Auth::user()->can('members.mamage')) {
			return response()->json(["msg" => "Keine Berechtigung"], 403);
		}

		$user->forceDelete();

		return response()->json([
			"message" => "Die Anmeldung wurde erfolgreich abgelehnt",
		], Response::HTTP_OK);
	}

	/**
	 * @param  \App\Http\Requests\Member\MemberRegisterRequest  $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \JsonException
	 */
	public function register(MemberRegisterRequest $request): JsonResponse
	{
		$user = User::create([
			'first_name'               => $request->firstName,
			'last_name'                => $request->lastName,
			'email'                    => $request->email,
			'birthday'                 => $request->birthday,
			'wants_email_notification' => $request->wantsEmailNotification,
			'password'                 => $request->password,
			'level_id'                 => Level::GUEST,
		]);

		$memberShip = MemberShip::create([
			'user_id'    => $user->id,
			'country_id' => Country::where("name", $request->country)->first()->id,
			'salutation' => $request->salutation,
			'phone'      => $request->phone,
			'street'     => $request->street,
			'postcode'   => $request->postcode,
			'city'       => $request->city,
			'iban'       => $request->iban,
			'bic'        => $request->bic,
		]);
		$branches = json_decode($request->branches, true, 512, JSON_THROW_ON_ERROR);

		$memberShip->branches()->sync([Branch::AIR, ...$branches]);

		$originalFileName = $request->file('signature')->getClientOriginalName();
		$image = ImageGenerator::resizeImageIfNeeded($request->file('signature'));

		$memberShip->signature()->create([
			'image'          => $image->encode('data-url'),
			'thumbnail'      => $image->encode('data-url'),
			'file_name'      => $originalFileName,
			'file_mime_type' => $image->mime(),
			'file_size'      => $image->filesize()
		]);

		$data = [
			'fullName'                     => $user->first_name.' '.$user->last_name,
			'street'                       => $memberShip->street,
			'postcode'                     => $memberShip->postcode,
			'city'                         => $memberShip->city,
			'country'                      => $request->country,
			'iban'                         => $memberShip->iban,
			'bic'                          => $memberShip->bic,
			'signature'                    => $image->encode('data-url'),
			'mandateReference'             => 'AIR '.$memberShip->id,
			'creditorIdentificationNumber' => env('CREDITOR_IDENTIFICATION_NUMBER')
		];

		$pdf = PDF::loadView('pdf/sepaPDF', $data);

		Storage::put('public/pdf/'.$user->first_name.'_'.$user->last_name.now()->format('Y-m-d').'.pdf',
			$pdf->output());

		$user->sendEmailVerificationNotification();
		//Notification::send(User::notification()->get(), new NewAppointmentNotification());

		return response()->json([
			"message" => "Deine Anfrage wurde erfolgreich erstellt. Bitte bestätige deine Email!! Wir werden deine Anfrage schnellstmöglich bearbeiten.",
		], Response::HTTP_OK);
	}
}
