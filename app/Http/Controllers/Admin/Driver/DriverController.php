<?php

namespace App\Http\Controllers\Admin\Driver;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    private Driver $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function index()
    {
        $drivers = $this->driver->all();
        return view('pages.driver.index', compact('drivers'));
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:drivers',
                'password' => 'required|string|min:6'
            ]);

            $this->driver->create([
                'name' => $validate['name'],
                'email' => $validate['email'],
                'password' => Hash::make($validate['password'])
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Driver registered successfully',
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $driver = $this->driver->find($id);
            $validate = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:drivers,email,' . $id,
                'password' => 'nullable|string|min:6'
            ]);

            if ($request->filled('password')) {
                $validate['password'] = Hash::make($validate['password']);
            } else {
                unset($validate['password']);
            }

            $driver->update($validate);

            return response()->json([
                'status' => 'success',
                'message' => 'Driver updated successfully',
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
            $driver = $this->driver->find($id);
            $driver->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Driver deleted successfully',
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
