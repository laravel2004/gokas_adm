<?php

namespace App\Http\Controllers\User\Loan;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Approval;
use App\Models\Employee;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    private Loan $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    public function index()
    {
        try {
            $employee = auth('api')->user();
            $account = Account::where('employee_id', $employee->id)->first();
            if (!$account) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Account not found!'
                ], 404);
            }

            $loans = Loan::where('account_id', $account->id)->orderBy('created_at', 'DESC')->get();

            foreach ($loans as $loan) {
                $loan->status = json_decode($loan->status);
            }

            return response()->json([
                'status' => true,
                'data' => $loans,
                'message' => 'Loans retrieved successfully!'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1000',
                'tenor' => 'required|integer|min:1',
                'instalment' => 'required|numeric|min:1',
                'description' => 'nullable|string|max:255',
            ]);

            $employee = auth('api')->user();
            $account = Account::where('employee_id', $employee->id)->first();

            $activeLoan = $this->loan->where('account_id', $account->id)->where('is_paid_off', false)->first();

            if ($activeLoan) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'This account already has an active loan.'
                ], 400);
            }

            $limitAvailable = $account->limit_loan - $account->limit_loan_used;

            $approval = Approval::where('employee_id', $account->employee_id)->first();
            if (!$approval) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'This account not member.'
                ], 400);
            }

            if ($validated['amount'] + ($validated['amount'] * 0.05) > $limitAvailable) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Requested amount exceeds available limit.'
                ], 400);
            }

            DB::beginTransaction();

            $loan = $this->loan->create([
                'account_id' => $account->id,
                'approval_id' => $approval->id,
                'amount' => $validated['amount'],
                'tenor' => $validated['tenor'],
                'instalment' => $validated['instalment'],
                'description' => $validated['description'] ?? null,
                'status' => json_encode(["Pengajuan"]),
                'paid_tenor' => 0,
            ]);

            $account->limit_loan_used += $validated['amount'] + ($validated['amount'] * 0.05);
            $account->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Successfull create loan request'
            ], 200);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
