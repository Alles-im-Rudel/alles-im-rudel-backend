<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostUserResource extends JsonResource
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
            'id'         => $this->id,
            'salutation' => $this->salutation,
            'firstName'  => $this->first_name,
            'lastName'   => $this->last_name,
            'fullName'   => $this->full_name,
            'birthday'   => $this->birthday ? Carbon::parse($this->birthday)->format('Y-m-d') : '',
            'age'        => $this->age,
            'createdAt'  => $this->created_at,

            'image'     => new ImageResource($this->whenLoaded('image')),
            'thumbnail' => new ImageResource($this->whenLoaded('thumbnail')),
        ];
    }
}
