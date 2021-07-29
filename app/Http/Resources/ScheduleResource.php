<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'doctor id' => $this->user_id,
            'Weekday' => $this->weekday,
            'Date' => $this->date,
            'start time' => $this->start_time,
            'end time' => $this->end_time,
        ];
    }
}
