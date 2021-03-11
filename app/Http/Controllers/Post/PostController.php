<?php

namespace App\Http\Controllers\Post;


use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
	/**
	 * @return AnonymousResourceCollection
	 */
	public function index(): AnonymousResourceCollection
	{
		$posts = Post::with(['user.thumbnail', 'tags', 'thumbnails'])->withCount('comments')->get();

		return PostResource::collection($posts);
	}

	/**
	 * @param  Post  $post
	 * @return PostResource
	 */
	public function show(Post $post): PostResource
	{
		$post->loadMissing(['user.thumbnail', 'tags', 'images'])->loadCount('comments');

		return new PostResource($post);
	}
}
