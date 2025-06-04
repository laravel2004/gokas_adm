<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DriverTaskResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($driverTask) {
            return [
                'id' => $driverTask->id,
                'driver_id' => $driverTask->driver_id,
                'start_pick_point_id' => $driverTask->start_pick_point_id,
                'end_pick_point_id' => $driverTask->end_pick_point_id,
                'status' => $driverTask->status,
                'end_date' => $driverTask->end_date,
                'end_time' => $driverTask->end_time,
                'start_pick_point' => $driverTask->startPickPoint->name,
                'end_pick_point' => $driverTask->endPickPoint->name,
            ];
        })->toArray();
    }
}
