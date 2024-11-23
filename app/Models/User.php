<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'AgentOrder', // Add AgentOrder to fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }
    public function agentStatus()
    {
        return $this->belongsTo(AgentStatus::class, 'agent_status_id'); // Define the foreign key explicitly if needed
    }

    public function agentStatusHistory()
    {
        return $this->hasMany(AgentStatusHistory::class, 'user_id');
    }

    public function hasRole($roles)
    {
        return $this->roles()->whereIn('roleName', (array) $roles)->exists();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'agent_roles', 'user_id', 'role_id');
    }


}
