<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
			'id'          => $this->id,
			'title'       => $this->title,
			'text'        => $this->text,
			'startAt'     => $this->getTimeFormat($this->start_at),
			'endAt'       => $this->getTimeFormat($this->end_at),
			'isAllDay'    => $this->is_all_day,
			'isBirthday'  => $this->is_birthday,
			'userId'      => $this->user_id,
			'birthdayId'  => $this->birthday_id,
			'color'       => $this->color,
			'user'        => new UserResource($this->whenLoaded('user')),
			'tags'        => new TagResouce($this->whenLoaded('taga')),
			'birthdayKid' => new UserResource($this->whenLoaded('birthdayKid')),
			'createdAt'   => $this->created_at,
			'updatedAt'   => $this->updated_at
		];
	}

	public function getTimeFormat($column): string
	{
		$format = $this->is_all_day ? 'Y-m-d' : 'Y-m-d H:i';
		return Carbon::parse($column)->format($format);
	}
}
