<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
	protected $year;

	/**
	 * Transform the resource into an array.
	 *
	 * @param $request
	 * @return array
	 */
	public function toArray($request): array
	{

		$this->year = $request->year;

		return [
			'id'          => $this->id,
			'title'       => $this->title,
			'text'        => $this->text,
			'startAt'     => $this->getTimeFormat($this->start_at),
			'endAt'       => $this->getTimeFormat($this->end_at),
			'isAllDay'    => $this->is_all_day,
			'dates'       => $this->getDates($this->start_at, $this->end_at),
			'fromTime'    => $this->getTime($this->start_at),
			'toTime'      => $this->getTime($this->end_at),
			'isBirthday'  => $this->is_birthday,
			'birthday'    => $this->is_birthday ? $this->start_at : null,
			'userId'      => $this->user_id,
			'birthdayId'  => $this->birthday_id,
			'color'       => $this->color,
			'likes'       => $this->when($this->likes_count !== null, $this->likes_count),
			'user'        => new UserResource($this->whenLoaded('user')),
			'tags'        => TagResouce::collection($this->whenLoaded('tags')),
			'birthdayKid' => new UserResource($this->whenLoaded('birthdayKid')),
			'createdAt'   => $this->created_at,
			'updatedAt'   => $this->updated_at
		];
	}

	protected function getTimeFormat($column): string
	{
		if ($this->is_birthday) {
			$column = $this->year.'-'.Carbon::parse($column)->format('m-d');
		}

		$format = $this->is_all_day ? 'Y-m-d' : 'Y-m-d H:i';
		return Carbon::parse($column)->format($format);
	}

	protected function getDates($start, $end): array
	{
		$start = Carbon::parse($start)->format('Y-m-d');
		$end = Carbon::parse($end)->format('Y-m-d');
		return [0 => $start, 1 => $end];
	}

	protected function getTime($dateTime): string
	{
		return Carbon::parse($dateTime)->format('h:m');
	}
}
