<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
			'imageableType' => $this->imageable_type,
			'imageableId'   => $this->imageable_id,
			'image'         => $this->image,
			'thumbnail'     => $this->thumbnail,
			'fileName'      => $this->file_name,
			'fileSize'      => $this->file_size,
			'fileMimeType'  => $this->file_mime_type,
			'title'         => $this->title,
			'sortOrder'     => $this->sort_order,
			'createdAt'     => $this->created_at,
			'updatedAt'     => $this->updated_at,
			'deletedAt'     => $this->deleted_at,
		];
	}
}
