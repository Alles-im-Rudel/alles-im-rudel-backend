<?php

namespace App\Http\Controllers\Minecraft\Whitelist;

use App\Http\Controllers\Controller;
use App\Models\MinecraftPlayer;
use Illuminate\Http\JsonResponse;

class MinecraftWhitelistController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $players = MinecraftPlayer::query()
            ->groupBy('name')
            ->select(['name'])
            ->get();

        $return = $players->map(static function ($item) {
            return $item->name;
        });

        return response()->json($return);
    }
}
