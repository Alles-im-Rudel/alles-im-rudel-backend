<?php

namespace App\Http\Controllers\Comment;


use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
	/**
	 * @param  Post  $post
	 * @return AnonymousResourceCollection
	 */
	public function byPost(Post $post): AnonymousResourceCollection
	{
		$comments = $post->comments()->with('user.thumbnail')->get();

		return CommentResource::collection($comments);
	}
}
