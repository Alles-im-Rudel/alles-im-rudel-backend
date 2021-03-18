<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
			'text'          => $this->text,
			'userId'        => $this->user_id,
			'user'          => new UserResource($this->whenLoaded('user')),
			'comments'      => self::collection($this->whenLoaded('comments')),
			'commentsCount' => $this->commentCount,
			'createdAt'     => $this->created_at,
			'updatedAt'     => $this->updated_at
		];
	}
}
