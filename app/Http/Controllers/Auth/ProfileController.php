<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileIndexRequest;
use App\Http\Requests\Auth\ProfileMainSummonerRequest;
use App\Http\Requests\Auth\ProfileUpdateBranchesRequest;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Summoner;
use App\Models\User;
use App\Services\Images\ImageGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function DeepCopy\deep_copy;


class ProfileController extends Controller
{
    /**
     * @param ProfileIndexRequest $request
     * @return UserResource
     */
    public function index(ProfileIndexRequest $request): UserResource
    {
        $userId = Auth::id();

        return new UserResource(User::with([
            'summoners', 'userGroups', 'mainSummoner', 'image', 'thumbnail', 'memberShip',
            'memberShip.branches' => function ($query) {
                $query->whereNull('branch_member_ship.wanted_to_leave_at');
            },
        ])->find($userId));
    }

    /**
     * @param \App\Http\Requests\Auth\ProfileUpdateRequest $request
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

        if ($request->password && $request->passwordRepeat) {
            if ($request->password !== $request->passwordRepeat) {
                return response()->json([
                    "message" => "Die Passwörter stimmen nicht überein."
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->memberShip->salutation = $request->salutation;
        $user->memberShip->phone = $request->phone;
        $user->memberShip->save();

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
     * @param \App\Http\Requests\Auth\ProfileUpdateBranchesRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBranches(ProfileUpdateBranchesRequest $request): JsonResponse
    {
        $user = User::where("id", Auth::id())->with([
            'memberShip', 'memberShip.branches' => function ($query) {
                $query->whereNull('branch_member_ship.wanted_to_leave_at');
            },
        ])->first();

        $newBranches = $request->branchIds;
        $originalBranches = collect(collect($user->memberShip->branches)->map(function ($item) {
            return $item->id;
        }));

        $toRemove = $originalBranches->diff($newBranches);
        $toAdd = collect($newBranches)->diff($originalBranches);

        if (
            DB::table('branch_member_ship')
                ->where('member_ship_id', $user->memberShip->id)
                ->whereNull('deleted_at')
                ->whereNotNull('wanted_to_leave_at')
                ->whereIn('branch_id', $toAdd)->exists()
        ) {
            DB::table('branch_member_ship')
                ->where('member_ship_id', $user->memberShip->id)
                ->whereNull('deleted_at')
                ->whereNotNull('wanted_to_leave_at')
                ->whereIn('branch_id', $toAdd)->update(['wanted_to_leave_at' => null]);
        } else {
            $user->memberShip->branches()->attach($toAdd);
        }
        if (count($toRemove) > 0) {
            DB::table('branch_member_ship')
                ->where('member_ship_id', $user->memberShip->id)
                ->whereIn('branch_id', $toRemove)
                ->update(['wanted_to_leave_at' => now()]);
        }

        $user->fresh();
        $user->load([
            'summoners', 'userGroups', 'mainSummoner', 'image', 'thumbnail', 'memberShip',
            'memberShip.branches' => function ($query) {
                $query->whereNull('branch_member_ship.wanted_to_leave_at');
            },
        ]);

        return response()->json([
            'message' => 'Die Sparten wurden erfolgreich bearbeitet.',
            'data'    => new UserResource($user),
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
     * @param Request $request
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
