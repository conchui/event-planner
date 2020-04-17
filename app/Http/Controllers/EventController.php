<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EventResource::collection(Event::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $eventId  = $request->input('eventId');

        $request->validate([
            'eventName'  => 'required',
            'startDate'  => 'required|date',
            'endDate'    => 'required|date|after:startDate',
            'daysActive' => 'required',
        ]);

        $event = (!is_null($eventId)) ?
            Event::findOrFail($eventId) : new Event();

        $daysActive = json_encode($request->input('daysActive'));
        $startDate  = $request->input('startDate');
        $endDate    = $request->input('endDate');

        $event->name        = $request->input('eventName');
        $event->start_date  = "{$startDate} 00:00:00";
        $event->end_date    = "{$endDate} 23:59:59";
        $event->active_days = $daysActive;
        
        if ($event->save()) {
            return $event->id;
        }
    }
}
