<?php

namespace App\Http\Controllers\Minecraft\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Minecraft\Player\MinecraftPlayerDeleteRequest;
use App\Http\Requests\Minecraft\Player\MinecraftPlayerIndexRequest;
use App\Http\Requests\Minecraft\Player\MinecraftPlayerStoreRequest;
use App\Http\Resources\MinecraftPlayerResource;
use App\Models\MinecraftPlayer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MinecraftPlayerController extends Controller
{
    /**
     * @param MinecraftPlayerIndexRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(MinecraftPlayerIndexRequest $request): AnonymousResourceCollection
    {
        $players = MinecraftPlayer::query()
            ->where('user_id', Auth::id())
            ->get();

        return MinecraftPlayerResource::collection($players);
    }

    /**
     * @param MinecraftPlayerStoreRequest $request
     * @return MinecraftPlayerResource|JsonResponse
     */
    public function store(MinecraftPlayerStoreRequest $request)
    {
        $exists = MinecraftPlayer::query()
            ->where('user_id', Auth::id())
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Dieser Spieler wurde bereits angelegt.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $player = MinecraftPlayer::query()->create([
            'name'    => $request->name,
            'user_id' => Auth::id()
        ]);

        return new MinecraftPlayerResource($player);
    }

    /**
     * @param MinecraftPlayerDeleteRequest $request
     * @return MinecraftPlayerResource|JsonResponse
     */
    public function delete(MinecraftPlayerDeleteRequest $request)
    {
        $player = MinecraftPlayer::query()
            ->where('user_id', Auth::id())
            ->find($request->minecraftPlayerId);

        if (!$player) {
            return response()->json([
                'message' => 'Dieser Spieler wurde bereits angelegt.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $player->delete();

        return new MinecraftPlayerResource($player);
    }
}
