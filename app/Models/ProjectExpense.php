<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_code',
        'project_id',
        'user_id',
        'category',
        'title',
        'description',
        'expense_date',
        'amount',
        'receipt_path',
        'is_billable_to_client',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'is_billable_to_client' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'transport_travel' => 'Transport & Dinas',
            'software_licenses' => 'Lisensi Software/Cloud',
            'equipment_materials' => 'Peralatan & Material',
            'meals_hospitality' => 'Konsumsi & Jamuan Klien',
            'subcontractor' => 'Subkontraktor/Outsource',
            default => 'Operasional Lainnya',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"><i class="fa-solid fa-circle-check mr-1.5 text-[10px]"></i>Disetujui</span>',
            'rejected' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20"><i class="fa-solid fa-circle-xmark mr-1.5 text-[10px]"></i>Ditolak</span>',
            'reimbursed' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20"><i class="fa-solid fa-money-bill-transfer mr-1.5 text-[10px]"></i>Direimburse</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20"><i class="fa-solid fa-clock mr-1.5 text-[10px]"></i>Menunggu Approval</span>',
        };
    }
}
