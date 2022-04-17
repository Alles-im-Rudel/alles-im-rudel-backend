<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'text'      => $this->text,
            'userId'    => $this->user_id,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,

            'likes' => $this->when($this->likes_count !== null, $this->likes_count),

            'user'      => new UserResource($this->whenLoaded('user')),
            'tag'       => new TagResouce($this->whenLoaded('tag')),
            'thumbnail' => new ImageResource($this->whenLoaded('thumbnail')),
            'image'     => new ImageResource($this->whenLoaded('image')),

            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
