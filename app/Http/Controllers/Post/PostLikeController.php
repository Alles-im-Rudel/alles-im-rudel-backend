<?php

namespace App\Http\Controllers\Post;


use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{

	/**
	 * @param  Post  $post
	 * @return JsonResponse
	 */
	public function checkLiked(Post $post): JsonResponse
	{
		return response()->json([
			'liked' => Auth::user()->hasLikedPost($post)
		]);
	}

	/**
	 * @param  Post  $post
	 * @return JsonResponse
	 */
	public function change(Post $post): JsonResponse
	{
		if (Auth::user()->hasLikedPost($post)) {
			Like::where('user_id', Auth::id())
				->where('likeable_id', $post->id)
				->where('likeable_type', get_class($post))->first()->delete();
			return response()->json([
				'liked' => false
			]);
		}
		$post->likes()->create([
			'user_id' => Auth::id()
		]);
		return response()->json([
			'liked' => true
		]);
	}

}
