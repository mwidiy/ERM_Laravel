<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_code',
        'company_name',
        'industry',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'tax_id',
        'payment_terms_days',
        'credit_limit',
        'status',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'payment_terms_days' => 'integer',
    ];

    public function contacts()
    {
        return $this->hasMany(ClientContact::class);
    }

    public function primaryContact()
    {
        return $this->hasOne(ClientContact::class)->where('is_primary', true);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function totalInvoiced(): float
    {
        return (float) $this->invoices()->where('status', '!=', 'cancelled')->sum('total_amount');
    }

    public function totalOutstandingAR(): float
    {
        return (float) $this->invoices()->where('status', '!=', 'cancelled')->sum('remaining_balance');
    }
}

