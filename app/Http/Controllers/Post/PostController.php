<?php

namespace App\Http\Controllers\Post;


use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostDeleteRequest;
use App\Http\Requests\Post\PostIndexRequest;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewPostNotification;
use App\Services\Images\ImageGenerator;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
	/**
	 * @param  PostIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(PostIndexRequest $request): AnonymousResourceCollection
	{
		$posts = Post::with([
			'user.thumbnail', 'tags', 'thumbnails'
		])->withCount('comments', 'likes')->orderByDesc('created_at');
		if ($request->tagIds && count($request->tagIds) > 0) {
			$posts = $posts->whereHas('tags', static function ($query) use ($request) {
				$query->whereIn('tags.id', $request->tagIds);
			});
		}
		if ($request->search) {
			$posts->where('title', 'like', "%{$request->search}%")
				->orWhere('created_at', 'like', "%{$request->search}%");
		}

		return PostResource::collection($posts->paginate(4, '*', $request->page, $request->page));
	}

	/**
	 * @param  Post  $post
	 * @return PostResource
	 */
	public function show(Post $post): PostResource
	{
		$post->loadMissing(['user.thumbnail', 'tags', 'images'])->loadCount('comments', 'likes');

		return new PostResource($post);
	}

	/**
	 * @param  PostStoreRequest  $request
	 * @return JsonResponse
	 */
	public function store(PostStoreRequest $request): JsonResponse
	{
		$post = Post::create([
			'title'   => $request->title,
			'text'    => $request->text,
			'user_id' => Auth::id()
		]);

		$post->tags()->sync($request->tagIds);

		Notification::send(User::notification()->get(), new NewPostNotification($post));

		return response()->json([
			'postId' => $post->id
		], Response::HTTP_CREATED);
	}

	/**
	 * @param  Post  $post
	 * @param  Request  $request
	 * @return JsonResponse
	 * @throws ValidationException
	 */
	public function storeImage(Post $post, Request $request): JsonResponse
	{
		if (!Auth::user()->can('posts.create')) {
			return response()->json([
				'message' => 'Keine Berechtigung'
			], Response::HTTP_UNAUTHORIZED);
		}

		$this->validate($request, [
			'file'  => 'required|mimes:jpg,jpeg,png,pdf|max:5120',
			'title' => 'nullable',
		]);
		$originalFileName = optional($request->file('file'))->getClientOriginalName();
		$image = ImageGenerator::resizeImageIfNeeded($request->file('file'));
		$thumbnail = ImageGenerator::createThumbnail($request->file('file'));
		$post->images()->create([
			'image'          => $image->encode('data-url'),
			'thumbnail'      => $thumbnail->encode('data-url'),
			'file_name'      => $originalFileName,
			'title'          => $request->title,
			'file_mime_type' => $image->mime(),
			'file_size'      => $image->filesize()
		]);

		return response()->json([
			'message' => 'Der Post wurde erfolgreich gespeichert!'
		]);
	}

	/**
	 * @param  Post  $post
	 * @param  PostUpdateRequest  $request
	 * @return JsonResponse
	 */
	public function update(Post $post, PostUpdateRequest $request): JsonResponse
	{
		$post->update([
			'title' => $request->title,
			'text'  => $request->text
		]);

		$post->tags()->sync($request->tagIds);

		$post->loadMissing(['user.thumbnail', 'tags', 'images'])->loadCount('comments');

		return response()->json([
			'post'    => new PostResource($post),
			'message' => 'Der Post wurde erfolgreich bearbeitet'
		], Response::HTTP_OK);
	}

	/**
	 * @param  Post  $post
	 * @param  PostDeleteRequest  $request
	 * @return JsonResponse
	 * @throws Exception
	 */
	public function delete(Post $post, PostDeleteRequest $request): JsonResponse
	{
		$post->tags()->detach();
		$post->delete();

		return response()->json([
			'message' => 'Der Post wurde erfolgreich gelöscht'
		]);
	}
}
