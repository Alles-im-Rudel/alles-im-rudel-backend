<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  Request  $request
	 * @return array
	 */
	public function toArray($request): array
	{
		return [
			'id'            => $this->id,
			'title'         => $this->title,
			'text'          => $this->text,
			'userId'        => $this->userId,
			'user'          => new UserResource($this->whenLoaded('user')),
			'comments'      => CommentResource::collection($this->whenLoaded('comments')),
			'commentsCount' => $this->when(isset($this->comments_count), $this->comments_count),
			'tags'          => TagResouce::collection($this->whenLoaded('tags')),
			'images'        => ImageResource::collection($this->whenLoaded('images')),
			'thumbnails'    => ImageResource::collection($this->whenLoaded('thumbnails')),
			'createdAt'     => $this->created_at,
			'updatedAt'     => $this->updated_at,
			'deletedAt'     => $this->deleted_at,
		];
	}
}