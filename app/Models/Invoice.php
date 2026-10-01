<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'project_id',
        'client_id',
        'milestone_id',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'withholding_tax_rate',
        'withholding_tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'remaining_balance',
        'status',
        'payment_terms',
        'bank_details',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'withholding_tax_rate' => 'decimal:2',
        'withholding_tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function milestone()
    {
        return $this->belongsTo(ProjectMilestone::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class)->orderBy('payment_date', 'desc');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Hitung ulang total subtotal, pajak, diskon, dan remaining balance
     */
    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items()->sum('total_price');
        $this->tax_amount = ($this->subtotal * ($this->tax_rate / 100));
        $this->withholding_tax_amount = ($this->subtotal * ($this->withholding_tax_rate / 100));
        
        // Total Faktur = Subtotal + PPN (11%) - PPh 23 (2%) - Diskon
        $netTotal = ($this->subtotal + $this->tax_amount) - $this->withholding_tax_amount - $this->discount_amount;
        $this->total_amount = max(0, $netTotal);

        $this->paid_amount = $this->payments()->sum('amount');
        $this->remaining_balance = max(0, $this->total_amount - $this->paid_amount);

        // Update status otomatis
        if ($this->status !== 'cancelled') {
            if ($this->paid_amount >= $this->total_amount && $this->total_amount > 0) {
                $this->status = 'paid';
            } elseif ($this->paid_amount > 0) {
                $this->status = 'partially_paid';
            } elseif ($this->due_date < now()->toDateString() && $this->status !== 'draft') {
                $this->status = 'overdue';
            }
        }

        $this->save();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'paid' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"><i class="fa-solid fa-check-double mr-1.5 text-[10px]"></i>Lunas (Paid)</span>',
            'partially_paid' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20"><i class="fa-solid fa-hourglass-half mr-1.5 text-[10px]"></i>Dibayar Sebagian</span>',
            'overdue' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20"><i class="fa-solid fa-triangle-exclamation mr-1.5 text-[10px]"></i>Jatuh Tempo (Overdue)</span>',
            'sent' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20"><i class="fa-solid fa-paper-plane mr-1.5 text-[10px]"></i>Terkirim (Unpaid)</span>',
            'cancelled' => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-500/10 text-slate-400 border border-slate-500/20"><i class="fa-solid fa-ban mr-1.5 text-[10px]"></i>Dibatalkan</span>',
            default => '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20"><i class="fa-solid fa-file-pen mr-1.5 text-[10px]"></i>Draft</span>',
        };
    }
}
