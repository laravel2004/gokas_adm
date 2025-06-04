<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackGps extends Model
{
    protected $guarded = ['id'];

    public function driverTask()
    {
        return $this->belongsTo(DriverTask::class, 'driver_task_id');
    }
}
