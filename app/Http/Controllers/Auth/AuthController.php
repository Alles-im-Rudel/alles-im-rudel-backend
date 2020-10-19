<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Psr\Http\Message\ResponseInterface;

class AuthController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();
        return response()->json([
            'user'        => new UserResource($user),
            'permissions' => PermissionResource::collection($user->getAllPermissions())
        ]);
    }

    /**
     * @param  AuthLoginRequest  $request
     * @return JsonResponse
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $user = User::where('email', '=', $request->email)->where('activated_at', '<>', null)->exists();

        if (!$user) {
            return response()->json([
                "message" => 'Kein Valider User'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $http = new Client();

        try {
            $tokens = $http->post(env('OAUTH2_AUTH_URL'), [
                'form_params' => [
                    'grant_type'    => 'password',
                    'client_id'     => (string) env('PASSPORT_CLIENT_ID'),
                    'client_secret' => (string) env('PASSPORT_CLIENT_SECRET'),
                    'username'      => $request->email,
                    'password'      => $request->password
                ],
            ]);
        } catch (GuzzleException $exception) {
            return response()->json([
                "message" => 'Passport Fehler',
                "asdf" => $request->email,
                "sdfgsdfg" => $request->password
            ], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $decodedTokens = json_decode($tokens->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            return response()->json([
                "message" => __('auth.failed')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $user = User::where('email', '=', $request->email)->where('activated_at', '<>', null)->first();
        return response()->json([
            'user'        => new UserResource($user),
            'tokens'      => $decodedTokens,
            'permissions' => PermissionResource::collection($user->getAllPermissions())
        ]);
    }

    /**
     * @param  AuthRegisterRequest  $request
     * @return JsonResponse
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $now = now();
        $user = User::create([
            'password'          => Hash::make($request->password),
            'email'             => $request->email,
            'username'          => $request->username,
            'email_verified_at' => $now,
            'activated_at'      => $now
        ]);

        $http = new Client();

        try {
            $tokens = $http->post(env('OAUTH2_AUTH_URL'), [
                'form_params' => [
                    'grant_type'    => 'password',
                    'client_id'     => (string) env('PASSPORT_CLIENT_ID'),
                    'client_secret' => (string) env('PASSPORT_CLIENT_SECRET'),
                    'username'      => $request->email,
                    'password'      => $request->password
                ],
            ]);
        } catch (GuzzleException $exception) {
            return response()->json([
                "message" => __('auth.failed')
            ], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $decodedTokens = json_decode($tokens->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            return response()->json([
                "message" => __('auth.failed')
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'user'        => new UserResource($user),
            'tokens'      => $decodedTokens,
            'permissions' => PermissionResource::collection($user->getAllPermissions())
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => 'Erfolgreich ausgeloggt.'
        ], Response::HTTP_OK);
    }
}
