<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'eventId'    => $this->id,
            'eventName'  => $this->name,
            'startDate'  => $this->start_date,
            'endDate'    => $this->end_date,
            'daysActive' => $this->active_days
        ];
    }
}
