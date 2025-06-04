<?php

namespace App\Http\Controllers\Driver\DriverTask;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverTaskResource;
use App\Models\DriverTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverTaskDriverController extends Controller
{
    private DriverTask $driverTask;

    public function __construct(DriverTask $driverTask)
    {
        $this->driverTask = $driverTask;
    }

    public function index(Request $request)
    {
        try {
            $driver = Auth::guard('api')->user();
            $driverTasks = $this->driverTask
                ->where('driver_id', $driver->id)->with('startPickPoint', 'endPickPoint')
                ->select('id', 'driver_id', 'start_pick_point_id', 'end_pick_point_id', 'end_date', 'end_time', 'status')->get();

            $data = new DriverTaskResource($driverTasks);
            return response()->json([
                'status' => 'success',
                'message' => 'Driver tasks fetched successfully',
                'data' => $data
            ], 200);

        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $driver = Auth::guard('api')->user();
            $driverTask = $this->driverTask
                ->where('driver_id', $driver->id)->with('startPickPoint', 'endPickPoint')
                ->select('id', 'driver_id', 'start_pick_point_id', 'end_pick_point_id', 'end_date', 'end_time', 'status')
                ->find($id);

            if (!$driverTask) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Driver task not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Driver task fetched successfully',
                'data' => $driverTask
            ], 200);

        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function startTask(Request $request)
    {
        try {
            $validate = $request->validate([
                'driver_task_id' => 'required|exists:driver_tasks,id'
            ]);

            $driverTask = $this->driverTask->find($validate['driver_task_id']);
            if (!$driverTask) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Driver task not found'
                ], 404);
            }

            $driverTasks = $this->driverTask->where('driver_id', $driverTask->driver_id)->where('status', 'on_progress')->get();
            if ($driverTasks->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Driver task already on progress'
                ], 400);
            }

            $driverTask->status = 'on_progress';
            $driverTask->start_date = now();
            $driverTask->start_time = now();
            $driverTask->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Driver task started successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function finishTask(Request $request)
    {
        try {
            $validate = $request->validate([
                'driver_task_id' => 'required|exists:driver_tasks,id'
            ]);

            $driverTask = $this->driverTask->find($validate['driver_task_id']);
            if (!$driverTask) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Driver task not found'
                ], 404);
            }

            $driverTask->status = 'finish';
            $driverTask->end_date = now();
            $driverTask->end_time = now();
            $driverTask->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Driver task finished successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
