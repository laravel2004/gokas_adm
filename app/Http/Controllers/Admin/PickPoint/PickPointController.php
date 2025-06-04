<?php

namespace App\Http\Controllers\Admin\PickPoint;

use App\Http\Controllers\Controller;
use App\Models\PickPoint;
use Illuminate\Http\Request;

class PickPointController extends Controller
{
    private PickPoint $pickPoint;

    public function __construct(PickPoint $pickPoint)
    {
        $this->pickPoint = $pickPoint;
    }

    public function index()
    {
        $pickPoints = $this->pickPoint->all();
        return view('pages.pick-point.index', compact('pickPoints'));
    }

    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|string',
                'longitude' => 'required|string',
                'latitude' => 'required|string',
                'address' => 'required|string'
            ]);

            $this->pickPoint->create($validate);

            return response()->json([
                'status' => 'success',
                'message' => 'Pick point registered successfully',
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
            $pickPoint = $this->pickPoint->find($id);
            $validate = $request->validate([
                'name' => 'required|string',
                'longitude' => 'required|string',
                'latitude' => 'required|string',
                'address' => 'required|string'
            ]);

            $pickPoint->update($validate);

            return response()->json([
                'status' => 'success',
                'message' => 'Pick point updated successfully',
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
            $pickPoint = $this->pickPoint->find($id);
            $pickPoint->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Pick point deleted successfully',
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
