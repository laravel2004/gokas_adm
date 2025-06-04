<?php

namespace App\Http\Controllers\User\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    private Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function index()
    {
        try {
            $employee = auth('api')->user();
            $account = Account::where('employee_id', $employee->id)->first();
            $invoices = $this->invoice->where('account_id', $account->id)->orderBy('created_at', 'desc')->get();
            foreach ($invoices as $invoice) {
                $invoice->content = json_decode($invoice->content);
                $invoice->created_at = $invoice->created_at->format('d-m-Y H:i:s');
                $invoice->updated_at = $invoice->updated_at->format('d-m-Y H:i:s');
            }

            return response()->json([
                'status' => true,
                'data' => $invoices,
                'message' => 'Invoices fetched successfully'
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => 'Failed to fetch invoices'
            ]);
        }
    }

    public function download(Request $request, $id)
    {
        $invoice = $this->invoice->findOrFail($id);
        $content = json_decode($invoice->content);
        $pdf = Pdf::loadView('pages.template.invoice', compact('invoice', 'content'));

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'invoice_' . $invoice->account->employee->name . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
