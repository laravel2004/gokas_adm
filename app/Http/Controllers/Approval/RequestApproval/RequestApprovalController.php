<?php

namespace App\Http\Controllers\Approval\RequestApproval;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Approval;
use App\Models\Employee;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class RequestApprovalController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $approval = Approval::where('head_employee_id', $user->id)->first();
            if (!$approval) {
                return response()->json([
                    'status' => false,
                    'data' => [],
                    'message' => 'Approval not found!'
                ], 200);
            }

            $loans = Loan::where('approval_id', $approval->id)
                ->where('is_approved', false)
                ->with('employee')
                ->orderBy('created_at', 'DESC')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $loans,
                'message' => 'Loans retrieved successfully!'
            ], 200);

        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function approve( $id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $status = json_decode($loan->status);
            $status[] = "Disetujui Approval";

            DB::beginTransaction();

            $loan->status = $status;
            $loan->is_approved = true;
            $loan->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Succesfully Approved Loan.'
            ], 200);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reject($id)
    {
        try {
            $loan = Loan::findOrFail($id);
            $account = Account::findOrFail($loan->account_id);
            $status = json_decode($loan->status);
            $status[] = "Pengajuan Ditolak";

            DB::beginTransaction();
            $account->limit_loan_used -= $loan->amount + ($loan->amount * 0.05);
            $loan->status = $status;
            $loan->is_paid_off = true;
            $loan->save();
            $account->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Succesfully reject loan.'
            ], 200);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
    }
}
