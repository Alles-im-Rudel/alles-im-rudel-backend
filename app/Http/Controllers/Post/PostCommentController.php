<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostCommentController extends Controller
{
    /**
     * @param Post $post
     * @return AnonymousResourceCollection
     */
    public function index(Post $post): AnonymousResourceCollection
    {
        $comments = $post->comments()->with('user.thumbnail', 'comments')->get();

        return CommentResource::collection($comments);
    }
}
