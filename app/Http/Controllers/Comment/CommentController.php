<?php

namespace App\Http\Controllers\Comment;


use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
	/**
	 * @param  Post  $post
	 * @return AnonymousResourceCollection
	 */
	public function byPost(Post $post): AnonymousResourceCollection
	{
		$comments = $post->comments()->with('user.thumbnail', 'comments')->get();

		return CommentResource::collection($comments);
	}

	/**
	 * @param  CommentStoreRequest  $request
	 * @return JsonResponse
	 */
	public function store(CommentStoreRequest $request): JsonResponse
	{
		try {
			$model = $request->modelType::find($request->modelId);
		} catch (Exception $exception) {
			return response()->json([
				"message" => 'Der Kommentar konnte nicht erstellt werden!'
			], Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		$model->comments()->create([
			'text'    => $request->comment,
			'user_id' => Auth::id()
		]);

		return response()->json([
			"message" => 'Der Kommentar wurde erfolgreich erstellt.'
		], Response::HTTP_CREATED);

	}
}
