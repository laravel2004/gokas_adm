<?php

namespace App\Http\Controllers\User\Paylater;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Paylater;
use Illuminate\Http\Request;

class PaylaterController extends Controller
{
    private Paylater $paylater;

    public function __construct(Paylater $paylater)
    {
        $this->paylater = $paylater;
    }

    public function index()
    {
        try {
            $employee = auth('api')->user();
            $account = Account::where('employee_id', $employee->id)->first();
            $paylaters = $this->paylater->where('account_id', $account->id)->orderBy('created_at', 'DESC')->get();

            foreach ($paylaters as $paylater) {
                $paylater->status = json_decode($paylater->status);
            }

            return response()->json([
                'status' => true,
                'data' => $paylaters,
                'message' => 'Paylater fetched successfully'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to fetch paylater'
            ]);
        }
    }
}
