<?php

namespace App\Http\Controllers\Admin\Invoice;

use App\Http\Controllers\Controller;
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
        $invoices = $this->invoice->all();
        return view('pages.invoice.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = $this->invoice->findOrFail($id);
        $content = json_decode($invoice->content);
        return view('pages.invoice.show', compact('invoice', 'content'));
    }

    public function download($id)
    {
        $invoice = $this->invoice->findOrFail($id);
        $content = json_decode($invoice->content);

        $pdf = Pdf::loadView('pages.template.invoice', compact('invoice', 'content'));
        return $pdf->download('invoice_' . $invoice->account->employee->name . '.pdf');
    }
}
