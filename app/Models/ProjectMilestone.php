<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'due_date',
        'completion_percentage',
        'billing_percentage',
        'billing_amount',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completion_percentage' => 'integer',
        'billing_percentage' => 'decimal:2',
        'billing_amount' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'milestone_id');
    }
}

