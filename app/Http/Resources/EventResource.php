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
            'id'         => $this->ID,
            'eventName'  => $this->NAME,
            'startDate'  => $this->START_DATE,
            'endDate'    => $this->END_DATE,
            'daysActive' => $this->ACTIVE_DAYS
        ];
    }
}
