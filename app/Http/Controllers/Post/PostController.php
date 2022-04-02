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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
	/**
	 * @param  PostIndexRequest  $request
	 * @return AnonymousResourceCollection
	 */
	public function index(PostIndexRequest $request): AnonymousResourceCollection
	{
		$posts = Post::with([
			'user.thumbnail',
            'tag',
            'thumbnail'
		])
            ->withCount('likes')
            ->orderByDesc('created_at');

		if ($request->tagIds && count($request->tagIds) > 0) {
			$posts = $posts->whereIn('tag_id', $request->tagIds);
		}

		if ($request->search) {
			$posts->where('title', 'like', "%{$request->search}%");
		}

		return PostResource::collection(
            $posts->paginate($request->perPage, '*', $request->page, $request->page)
        );
	}

	/**
	 * @param  Post  $post
	 * @return PostResource
	 */
	public function show(Post $post): PostResource
	{
		$post->loadMissing([
            'user.thumbnail',
            'tag',
            'image'
        ])->loadCount('likes');

		return new PostResource($post);
	}

	/**
	 * @param  PostStoreRequest  $request
	 * @return PostResource
	 */
	public function store(PostStoreRequest $request): PostResource
	{
		$post = Post::create([
			'title'   => $request->title,
			'text'    => $request->text,
            'tag_id'  => $request->tagId,
            'user_id' => Auth::id(),
		]);

        $originalFileName = $request->file('image')->getClientOriginalName();
        $image = ImageGenerator::resizeImageIfNeeded($request->file('image'));
        $thumbnail = ImageGenerator::createThumbnail($request->file('image'));

        $post->image()->create([
            'image'          => $image->encode('data-url'),
            'thumbnail'      => $thumbnail->encode('data-url'),
            'file_name'      => $originalFileName,
            'file_mime_type' => $image->mime(),
            'file_size'      => $image->filesize()
        ]);

		Notification::send(User::notification()->get(), new NewPostNotification($post));

		return new PostResource($post);
	}

	/**
	 * @param  Post  $post
	 * @param  PostUpdateRequest  $request
	 * @return PostResource
	 */
	public function update(Post $post, PostUpdateRequest $request): PostResource
	{
		$post->update([
			'title' => $request->title,
			'text'  => $request->text,
            'tag_id'  => $request->tagId,
		]);

        if ($request->file('image')) {
            $originalFileName = $request->file('image')->getClientOriginalName();
            $image = ImageGenerator::resizeImageIfNeeded($request->file('image'));
            $thumbnail = ImageGenerator::createThumbnail($request->file('image'));

            $post->image()->update([
                'image'          => $image->encode('data-url'),
                'thumbnail'      => $thumbnail->encode('data-url'),
                'file_name'      => $originalFileName,
                'file_mime_type' => $image->mime(),
                'file_size'      => $image->filesize()
            ]);
        }

		$post->loadMissing(['user.thumbnail', 'tag', 'image'])->loadCount('comments');

		return new PostResource($post);
	}

	/**
	 * @param  Post  $post
	 * @param  PostDeleteRequest  $request
	 * @return PostResource
	 * @throws Exception
	 */
	public function delete(Post $post, PostDeleteRequest $request): PostResource
	{
		$post->delete();

		return new PostResource($post);
	}
}
