<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverTask extends Model
{
    protected $guarded = ['id'];

    public function startPickPoint()
    {
        return $this->belongsTo(PickPoint::class, 'start_pick_point_id');
    }

    public function endPickPoint()
    {
        return $this->belongsTo(PickPoint::class, 'end_pick_point_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
