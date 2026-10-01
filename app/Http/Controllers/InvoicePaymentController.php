<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoicePaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice)
    {
        $maxAllowed = (float) $invoice->remaining_balance;

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1000|max:' . ($maxAllowed > 0 ? $maxAllowed : 9999999999),
            'payment_method' => 'required|in:bank_transfer,check_giro,credit_card,cash,other',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $proofPath = null;
            if ($request->hasFile('proof_of_payment')) {
                $proofPath = $request->file('proof_of_payment')->store('payment_proofs', 'public');
            }

            $yearMonth = date('Ym');
            $count = InvoicePayment::where('payment_number', 'LIKE', "PAY-{$yearMonth}-%")->count() + 1;
            $paymentNumber = sprintf("PAY-%s-%04d", $yearMonth, $count);

            $recorderId = User::first()->id ?? null;

            InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'payment_number' => $paymentNumber,
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'] ?? null,
                'proof_of_payment' => $proofPath,
                'notes' => $validated['notes'] ?? null,
                'recorded_by' => $recorderId,
            ]);

            // Recalculate invoice totals and payment status
            $invoice->recalculateTotals();

            // Jika linked ke milestone dan invoice lunas, kita tandai milestone completed jika belum
            if ($invoice->status === 'paid' && $invoice->milestone_id) {
                $milestone = $invoice->milestone;
                if ($milestone && $milestone->status !== 'completed') {
                    $milestone->update([
                        'status' => 'completed',
                        'completion_percentage' => 100,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)
                ->with('success', "Pelunasan pembayaran {$paymentNumber} sebesar Rp " . number_format($validated['amount'], 0, ',', '.') . " berhasil dicatat. Status tagihan terupdate.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function destroy(InvoicePayment $payment)
    {
        $invoice = $payment->invoice;

        if ($payment->proof_of_payment) {
            Storage::disk('public')->delete($payment->proof_of_payment);
        }

        $payment->delete();

        // Hitung ulang status invoice setelah pembayaran dihapus
        $invoice->recalculateTotals();

        return redirect()->route('invoices.show', $invoice->id)
            ->with('info', 'Catatan pelunasan berhasil dihapus dan saldo piutang disesuaikan kembali.');
    }
}
