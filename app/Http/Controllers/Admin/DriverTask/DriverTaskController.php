<?php

namespace App\Http\Controllers\Admin\DriverTask;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverTask;
use App\Models\PickPoint;
use Illuminate\Http\Request;

class DriverTaskController extends Controller
{
    private DriverTask $driverTask;
    private Driver $driver;
    private PickPoint $pickPoint;

    public function __construct(DriverTask $driverTask, Driver $driver, PickPoint $pickPoint)
    {
        $this->driverTask = $driverTask;
        $this->driver = $driver;
        $this->pickPoint = $pickPoint;
    }

    public function index()
    {
        $driverTasks = $this->driverTask->with('driver', 'startPickPoint', 'endPickPoint')->get();
        return view('pages.driver-task.index', compact('driverTasks'));
    }

    public function create()
    {
        $driverNames = $this->driver->select('id', 'name')->get();
        $pickPoints = $this->pickPoint->select('id', 'name', 'address')->get();
        return view('pages.driver-task.create', compact('driverNames', 'pickPoints'));
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'driver_id' => 'required|integer',
                'start_pick_point_id' => 'required|integer',
                'end_pick_point_id' => 'required|integer',
                'status' => 'required|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
            ]);

            $this->driverTask->create($validate);

            return response()->json([
                'status' => 'success',
                'message' => 'Driver task registered successfully',
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $driverTask = $this->driverTask->find($id);
        $driverNames = $this->driver->select('id', 'name')->get();
        $pickPoints = $this->pickPoint->select('id', 'name', 'address')->get();
        return view('pages.driver-task.edit', compact('driverTask', 'driverNames', 'pickPoints'));
    }

    public function update(Request $request, $id)
    {
        try {
            $driverTask = $this->driverTask->find($id);
            $validate = $request->validate([
                'driver_id' => 'required|integer',
                'start_pick_point_id' => 'required|integer',
                'end_pick_point_id' => 'required|integer',
                'status' => 'required|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
            ]);

            $driverTask->update($validate);

            return response()->json([
                'status' => 'success',
                'message' => 'Driver task updated successfully',
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $driverTask = $this->driverTask->find($id);
            $driverTask->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Driver task deleted successfully',
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
