<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionRequestForm;
use App\Models\Account;
use App\Models\Paylater;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function index()
    {
        $transactions = $this->transaction->all();
        return view('pages.transaction.index', compact('transactions'));
    }

    public function store(TransactionRequestForm $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();
            if($validated['method'] == 'paylater') {
                $account = Account::findOrFail($validated['account_id']);
                $available_limit = $account->limit_paylater - $account->limit_paylater_used;

                if ($available_limit < $validated['amount']) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Paylater limit exceeded',
                        'data' => null,
                    ], 400);
                }

                $interest = $validated['amount'] * 0.05;
                $paylater = Paylater::create([
                    'account_id' => $validated['account_id'],
                    'nominal' => $validated['amount'],
                    'interest' => $interest,
                    'is_paid_off' => false,
                    'description' => $validated['description'],
                    'total_amount' => $validated['amount'] + $interest,
                    'status' => json_encode(["UnPaid"]),
                ]);

                $point = $validated['amount'] / 1000;

                $transaction = $this->transaction->create([
                    'account_id' => $validated['account_id'],
                    'amount' => $validated['amount'],
                    'add_point' => $point,
                    'method' => $validated['method'],
                    'description' => $validated['description'],
                    'paylater_id' => $paylater->id,
                ]);

                $account->update([
                    'point' => $account->point + $point,
                    'limit_paylater_used' => $account->limit_paylater_used + $paylater->total_amount,
                ]);
            }
            else {
                $point = $validated['amount'] / 1000;

                $transaction = $this->transaction->create([
                    'account_id' => $validated['account_id'],
                    'amount' => $validated['amount'],
                    'add_point' => $point,
                    'method' => $validated['method'],
                    'description' => $validated['description'],
                ]);

                $account = Account::findOrFail($validated['account_id']);
                $account->update([
                    'point' => $account->point + $point,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaction successful',
                'data' => null,
            ]);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Transaction failed',
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
