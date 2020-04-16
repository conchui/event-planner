<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'EVENT';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'NAME',
        'START_DATE',
        'END_DATE',
        'ACTIVE_DAYS'
    ];
}
