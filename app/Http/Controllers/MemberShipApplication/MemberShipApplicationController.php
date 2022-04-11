<?php

namespace App\Http\Controllers\MemberShipApplication;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberShipApplication\MemberShipApplicationRequest;
use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\BranchUserMemberShip;
use App\Models\Country;
use App\Models\Level;
use App\Models\User;
use App\Services\Images\ImageGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use function env;
use function now;
use function response;

class MemberShipApplicationController extends Controller
{
	/**
	 * @param  \App\Http\Requests\MemberShipApplication\MemberShipApplicationRequest  $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \JsonException
	 */
	public function index(MemberShipApplicationRequest $request): JsonResponse
	{
		$bankAccount = BankAccount::create([
			'first_name'     => $request->accountFirstName,
			'last_name'      => $request->accountLastName,
			'street'         => $request->accountStreet,
			'postcode'       => $request->accountPostcode,
			'city'           => $request->accountCity,
			'bic'            => $request->bic,
			'iban'           => $request->iban,
			'country_id'     => Country::where("name", $request->accountCountry)->first()->id,
			'signature_city' => $request->accountSignatureCity,
		]);

		$originalFileName = $request->file('signature')->getClientOriginalName();
		$signature = ImageGenerator::resizeImageIfNeeded($request->file('signature'));

		$bankAccount->signature()->create([
			'image'          => $signature->encode('data-url'),
			'thumbnail'      => $signature->encode('data-url'),
			'file_name'      => $originalFileName,
			'file_mime_type' => $signature->mime(),
			'file_size'      => $signature->filesize()
		]);

		$user = User::create([
			'salutation'               => $request->salutation,
			'first_name'               => $request->firstName,
			'last_name'                => $request->lastName,
			'email'                    => $request->email,
			'phone'                    => $request->phone,
			'street'                   => $request->street,
			'postcode'                 => $request->postcode,
			'city'                     => $request->city,
			'birthday'                 => $request->birthday,
			'wants_email_notification' => $request->wantsEmailNotification,
			'country_id'               => Country::where("name", $request->country)->first()->id,
			'back_account_id'          => $bankAccount->id,
			'password'                 => Hash::make($request->password),
			'level_id'                 => Level::GUEST,
		]);

		$branches = json_decode($request->branches, true, 512, JSON_THROW_ON_ERROR);
		foreach ($branches as $branch) {
			BranchUserMemberShip::create([
				'user_id'   => $user->id,
				'branch_id' => $branch
			]);
		}

		$data = [
			'fullName'                     => $user->account_first_name.' '.$user->account_last_name,
			'street'                       => $bankAccount->account_street,
			'postcode'                     => $bankAccount->account_postcode,
			'city'                         => $bankAccount->account_city,
			'country'                      => $request->accountCountry,
			'iban'                         => $bankAccount->iban,
			'bic'                          => $bankAccount->bic,
			'accountSignatureCity'         => $bankAccount->account_signature_city,
			'signature'                    => $signature->encode('data-url'),
			'mandateReference'             => 'AIR '.$bankAccount->id,
			'creditorIdentificationNumber' => env('CREDITOR_IDENTIFICATION_NUMBER')
		];

		$pdf = PDF::loadView('pdf/sepaPDF', $data);

		Storage::put('public/pdf/'.'AIR_'.$bankAccount->id.'_'.$user->first_name.'_'.$user->last_name.now()->format('Y-m-d').'.pdf',
			$pdf->output());

		$user->sendEmailVerificationNotification();

		return response()->json([
			"message" => "Deine Anfrage wurde erfolgreich erstellt. Bitte bestätige deine Email!! Wir werden deine Anfrage schnellstmöglich bearbeiten.",
		], Response::HTTP_OK);
	}
}
