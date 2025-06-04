<?php

namespace App\Http\Controllers\User\Payment;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Employee;
use App\Models\Paylater;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PaymentController extends Controller
{

    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'account_id' => 'required|exists:accounts,id',
                'amount' => 'required|numeric|min:0',
                'method' => 'required|string|in:cash,paylater',
                'description' => 'required|string|max:255',
            ]);

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

    public function payment(Request $request)
    {
        try {
            $validated = $request->validate([
                "password" => "required|string",
            ]);

            $user = auth('api')->user();

            if ($user->role !== "user") {
                return response()->json([
                    'status' => false,
                    'message' => 'User is not authorized to make payments'
                ], 403);
            }

            if (!Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password is incorrect'
                ], 401);
            }

            $key = "secretkey1234567"; // Panjang 16, 24, atau 32 karakter
            $iv = openssl_random_pseudo_bytes(16); // 16 bytes untuk AES-256-CBC
            $cipher = "AES-256-CBC";

            $encryptedRaw = openssl_encrypt($user->id, $cipher, $key, OPENSSL_RAW_DATA, $iv);
            $sign = base64_encode($iv . $encryptedRaw); // gabungkan IV dan ciphertext

            return response()->json([
                'status' => true,
                'message' => 'Payment processed successfully',
                'data' => [
                    'sign' => $sign,
                    'id'   => $user->id,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error processing payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function comparePayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'sign' => 'required|string',
            ]);

            $key = "secretkey1234567"; // Sama seperti saat enkripsi
            $cipher = "AES-256-CBC";

            $signRaw = base64_decode($validated['sign']);

            $iv = substr($signRaw, 0, 16); // ambil IV dari awal string
            $encryptedRaw = substr($signRaw, 16); // sisanya adalah ciphertext

            $id = openssl_decrypt($encryptedRaw, $cipher, $key, OPENSSL_RAW_DATA, $iv);

            if (!$id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid signature or decryption failed',
                ], 400);
            }

            $user = Employee::findOrFail($id);
            $account = Account::where('employee_id', $id)->first();

            return response()->json([
                'status' => true,
                'message' => 'Payment signature matches',
                'data' => [
                    "user" => $user,
                    "account" => $account,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error comparing payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
