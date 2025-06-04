<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Employee::where('email', $request->email)->first();
        if($user->role != 'user') {
            return response()->json([
                'message' => 'Forbiden for users'
            ], 403);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 * 10
        ]);
    }

    public function me()
    {
        return response()->json(\auth('api')->user());
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function getInfo()
    {
        try {
            $employee = auth('api')->user();
            $account = Account::where('employee_id', $employee->id)->first();

            $data = [
                'name' => $employee->name,
                'position' => $employee->position,
                'department' => $employee->department,
                'limit_paylater_used' => $account->limit_paylater_used + 0,
                'limit_credit_used' => $account->limit_credit_used + 0,
                'limit_loan_used' => $account->limit_loan_used + 0,
                'limit_paylater_available' => $account->limit_paylater - $account->limit_paylater_used,
                'limit_credit_available' => $account->limit_credit - $account->limit_credit_used,
                'limit_loan_available' => $account->limit_loan - $account->limit_loan_used,
            ];

            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'User info fetched successfully'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to fetch user info'
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        try{
            $validated = $request->validate([
                'old_password' => 'required|string',
                'new_password' => 'required|string|min:8',
            ]);

            $user = auth('api')->user();

            if (!Hash::check($validated['old_password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Old password is incorrect'
                ]);
            }

            if ($validated['old_password'] === $validated['new_password']) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'New password cannot be the same as old password'
                ]);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Password changed successfully'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to change password'
            ]);
        }
    }
}
