<?php

namespace App\Http\Controllers\Admin\Paylater;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Paylater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaylaterController extends Controller
{
    private Paylater $paylater;

    public function __construct(Paylater $paylater)
    {
        $this->paylater = $paylater;
    }

    public function index()
    {
        $accounts = Account::all();
        return view('pages.paylater.index', compact('accounts'));
    }

    public function needPaid()
    {
        $accounts = Account::whereHas('paylaters', function ($query) {
            $query->where('is_paid_off', false);
        })->get();

        return view('pages.paylater.need-paid', compact('accounts'));
    }

    public function show($id)
    {
        $filter = request()->query('filter'); // ambil query string filter

        $query = $this->paylater->where('account_id', $id);

        if ($filter == 'unpaid') {
            $query->where('is_paid_off', false);
        } elseif ($filter == 'paid') {
            $query->where('is_paid_off', true);
        }

        $paylaters = $query->get();
        $account = Account::findOrFail($id);

        return view('pages.paylater.show', compact('paylaters', 'account', 'filter'));
    }

    public function paidOff($id)
    {
        try {
            $paylater = $this->paylater->findOrFail($id);
            $status = json_decode($paylater->status);
            $status[] = "Paid";
            DB::beginTransaction();
            $paylater->update([
                'is_paid_off' => true,
                'status' => json_encode($status)
            ]);

            $account = Account::findOrFail($paylater->account_id);
            $account->update([
                'limit_paylater_used' => $account->limit_paylater_used - $paylater->total_amount
            ]);

            $content = [
                'unit' => [
                    [
                        'date' => $paylater->created_at,
                        'nominal' => $paylater->nominal,
                        'interest' => $paylater->interest,
                        'amount' => $paylater->total_amount,
                    ]
                ],
                'total' => $paylater->total_amount,
            ];

            Invoice::create([
                'account_id' => $paylater->account_id,
                'type' => 'paylater',
                'content' => json_encode($content),
                'status' => 'paid',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Paylater paid off successfully',
                'data' => null
            ], 200);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkPaidOff($id)
    {
        try {
            $paylaters = $this->paylater->where('account_id', $id)->where('is_paid_off', false)->get();
            DB::beginTransaction();
            $total = 0;
            $unit = [];
            foreach ($paylaters as $paylater) {
                $status = json_decode($paylater->status);
                $status[] = "Paid";
                $paylater->update([
                    'is_paid_off' => true,
                    'status' => json_encode($status)
                ]);

                $total += $paylater->total_amount;
                $unit[] = [
                    'date' => $paylater->created_at,
                    'nominal' => $paylater->nominal,
                    'interest' => $paylater->interest,
                    'amount' => $paylater->total_amount,
                ];
            }
            $account = Account::findOrFail($id);
            $account->update([
                'limit_paylater_used' => $account->limit_paylater_used - $total
            ]);

            $content = [
                'unit' => $unit,
                'total' => $total,
            ];

            Invoice::create([
                'account_id' => $id,
                'type' => 'paylater',
                'content' => json_encode($content),
                'status' => 'paid',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Paylater paid off successfully',
                'data' => null
            ], 200);
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
