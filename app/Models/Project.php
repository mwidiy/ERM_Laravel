<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_code',
        'client_id',
        'project_manager_id',
        'name',
        'description',
        'contract_type',
        'contract_value',
        'budget_cost',
        'start_date',
        'end_date',
        'progress_percentage',
        'status',
        'priority',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'contract_value' => 'decimal:2',
        'budget_cost' => 'decimal:2',
        'progress_percentage' => 'integer',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class)->orderBy('due_date', 'asc');
    }

    public function projectMembers()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')
            ->withPivot('project_role', 'hourly_rate', 'assigned_date')
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class)->orderBy('due_date', 'asc');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class)->orderBy('entry_date', 'desc');
    }

    public function expenses()
    {
        return $this->hasMany(ProjectExpense::class)->orderBy('expense_date', 'desc');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class)->orderBy('issue_date', 'desc');
    }

    public function approvedLaborCost(): float
    {
        return (float) $this->timesheets()->where('status', 'approved')->sum('total_cost');
    }

    public function totalHoursLogged(): float
    {
        return (float) $this->timesheets()->where('status', 'approved')->sum('hours');
    }

    public function approvedDirectExpenses(): float
    {
        return (float) $this->expenses()->whereIn('status', ['approved', 'reimbursed'])->sum('amount');
    }

    public function totalActualCost(): float
    {
        return $this->approvedLaborCost() + $this->approvedDirectExpenses();
    }

    public function grossProfit(): float
    {
        return (float) $this->contract_value - $this->totalActualCost();
    }

    public function grossProfitMargin(): float
    {
        if ($this->contract_value <= 0) return 0;
        return round(($this->grossProfit() / $this->contract_value) * 100, 1);
    }

    public function totalInvoiced(): float
    {
        return (float) $this->invoices()->where('status', '!=', 'cancelled')->sum('total_amount');
    }

    public function totalCollected(): float
    {
        return (float) $this->invoices()->where('status', '!=', 'cancelled')->sum('paid_amount');
    }

    public function outstandingAR(): float
    {
        return (float) $this->invoices()->where('status', '!=', 'cancelled')->sum('remaining_balance');
    }
}

