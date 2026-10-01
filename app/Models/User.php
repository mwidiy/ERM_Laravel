<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'position',
        'hourly_rate',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function projectMemberships()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_members')->withPivot('project_role', 'hourly_rate');
    }

    public function assignedTasks()
    {
        return $this->hasMany(ProjectTask::class, 'assigned_to');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function expenses()
    {
        return $this->hasMany(ProjectExpense::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isProjectManager(): bool
    {
        return in_array($this->role, ['super_admin', 'project_manager']);
    }

    public function isFinance(): bool
    {
        return in_array($this->role, ['super_admin', 'finance']);
    }

    public function hasRole(array|string $roles): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super admin selalu memiliki akses ke seluruh role
        }

        $roles = is_array($roles) ? $roles : explode(',', $roles);
        return in_array($this->role, $roles);
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
        return $initials ?: 'US';
    }

    public function getRoleBadgeAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => '<span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-rose-500/10 text-rose-400 border border-rose-500/30"><i class="fa-solid fa-crown mr-1 text-[9px]"></i>Super Admin</span>',
            'admin' => '<span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">Admin</span>',
            'project_manager' => '<span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-sky-500/10 text-sky-400 border border-sky-500/30"><i class="fa-solid fa-list-check mr-1 text-[9px]"></i>Project Manager</span>',
            'finance' => '<span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/30"><i class="fa-solid fa-coins mr-1 text-[9px]"></i>Finance & Tax</span>',
            default => '<span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-violet-500/10 text-violet-400 border border-violet-500/30"><i class="fa-solid fa-laptop-code mr-1 text-[9px]"></i>Consultant</span>',
        };
    }
}

