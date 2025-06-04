<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    private Account $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function storeBalance(Request $request)
    {
        try {
            $validated = $request->validate([
                'balance' => 'required|numeric'
            ]);

            DB::beginTransaction();
            $accounts = $this->account->all();
            foreach ($accounts as $account) {
                $account->balance += $validated['balance'];
                $account->last_balance_in = Date::now();
                $account->save();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Balance added successfully',
                'data' => null
            ], 200);


        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error adding balance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $accounts = $this->account->all();
        return view('pages.account.index', compact('accounts'));
    }

    public function edit($id)
    {
        $account = $this->account->findOrFail($id);
        $employee = Employee::findOrFail($account->employee_id);
        $positions = Position::all();

        return view('pages.account.edit', compact('account', 'employee', 'positions'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'limit_paylater' => 'required',
                'limit_loan' => 'required',
                'limit_credit' => 'required',
                'point' => 'required'
            ]);

            $account = $this->account->findOrFail($id);
            $account->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Account updated successfully',
                'data' => null
            ], 200);
        }
        catch (\Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating account',
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
