<?php

namespace App\Http\Controllers\Admin\SettingLimit;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingLimit\SettingLimitRequestStoreForm;
use App\Models\Position;
use App\Models\SettingLimit;
use Illuminate\Http\Request;

class SettingLimitController extends Controller
{
    private SettingLimit $settingLimit;

    public function __construct(SettingLimit $settingLimit)
    {
        $this->settingLimit = $settingLimit;
    }

    public function index()
    {
        $positions = Position::all();
        $settingLimits = $this->settingLimit->all();
        return view('pages.setting-limit.index', compact('settingLimits', 'positions'));
    }

    public function store(SettingLimitRequestStoreForm $request)
    {
        try {
            $validated = $request->validated();
            $this->settingLimit->create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Setting limit created successfully',
                'data' => null
            ], 201);
        }
        catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating setting limit',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function update(SettingLimitRequestStoreForm $request, $id)
    {
        try {
            $validated = $request->validated();
            $settingLimit = $this->settingLimit->findOrFail($id);
            $settingLimit->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Setting limit updated successfully',
                'data' => null
            ], 200);
        }
        catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating setting limit',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $settingLimit = $this->settingLimit->findOrFail($id);
            $settingLimit->delete();

            return response()->json([
                'status' => true,
                'message' => 'Setting limit deleted successfully',
                'data' => null
            ], 200);
        }
        catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting setting limit',
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
