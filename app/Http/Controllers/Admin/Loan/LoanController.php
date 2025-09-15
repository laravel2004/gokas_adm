<?php

namespace App\Http\Controllers\Admin\Loan;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Approval;
use App\Models\Employee;
use App\Models\Invoice;
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

    public function index(Request $request)
    {
        $statusFilter = $request->input('status');

        $loan = $this->loan->orderBy('created_at', 'desc');

        if($statusFilter == 'Pengajuan') {
            $loan->where('status', '["Pengajuan"]')->get();
        }
        else if($statusFilter == 'Disetujui Approval') {
            $loan->where('status', '["Pengajuan","Disetujui Approval"]')->get();
        }

        $loans = $loan->get();

        return view('pages.loan.index', compact('loans', 'statusFilter'));
    }


    public function create()
    {
        $accounts = Account::all();
        return view('pages.loan.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'account_id' => 'required|exists:accounts,id',
                'approval_id' => 'nullable',
                'amount' => 'required|numeric|min:1000',
                'tenor' => 'required|integer|min:1',
                'instalment' => 'required|numeric|min:1',
                'description' => 'nullable|string|max:255',
            ]);

            $activeLoan = $this->loan->where('account_id', $validated['account_id'])
                ->where('is_paid_off', false)
                ->first();

            if ($activeLoan) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'This account already has an active loan.'
                ], 400);
            }

            $account = Account::find($validated['account_id']);
            $limitAvailable = $account->limit_loan - $account->limit_loan_used;

            $approval = Approval::where('employee_id', $account->employee_id)->first();
            if (!$approval) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'This account not member.'
                ], 400);
            }

            if ($validated['amount'] > $limitAvailable) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'message' => 'Requested amount exceeds available limit.'
                ], 400);
            }

            DB::beginTransaction();

            $loan = $this->loan->create([
                'account_id' => $validated['account_id'],
                'approval_id' => $validated['approval_id'],
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

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Error creating loan request: '.$e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $loan = $this->loan->findOrFail($id);
        return view('pages.loan.show', compact('loan'));
    }

    public function payInstalment($id)
    {
        try {
            $loan = $this->loan->findOrFail($id);
            $account = Account::findOrFail($loan->account_id);
            $status = json_decode($loan->status);

            DB::beginTransaction();
            $paid_tenor = $loan->paid_tenor + 1;
            $status[] = "Cicilan " . $paid_tenor;

            $flag = "Cicilan" . $paid_tenor;

            if ($paid_tenor == $loan->tenor) {
                $status[] = "Lunas";
                $loan->is_paid_off = true;
                $account->limit_loan_used -= $loan->amount + ($loan->amount * 0.05);
                $flag = "Lunas";
            }

            $interest = $loan->interest * 0.05;

            $content = [
                'unit' => [
                    [
                        'date' => $loan->created_at,
                        'nominal' => $loan->instalment,
                        'interest' => $interest,
                        'amount' => $loan->instalment + $interest,
                    ]
                ],
                'total' => $loan->instalment + $interest,
            ];

            Invoice::create([
                'account_id' => $account->id,
                'type' => 'loan',
                'content' => json_encode($content),
                'status' => $flag,
            ]);

            $loan->status = $status;
            $loan->paid_tenor = $paid_tenor;
            $loan->save();
            $account->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Succesfully Pay loan.'
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

    public function bypassApprover($id)
    {
        try {
            $loan = $this->loan->findOrFail($id);
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

    public function cashOut($id)
    {
        try {
            $loan = $this->loan->findOrFail($id);
            $status = json_decode($loan->status);
            $status[] = "Pencairan Dana";

            DB::beginTransaction();
            $loan->status = $status;
            $loan->is_approved_admin = true;
            $loan->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => null,
                'message' => 'Succesfully Cashout loan.'
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
            $loan = $this->loan->findOrFail($id);
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
