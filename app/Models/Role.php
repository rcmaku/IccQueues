<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'roleName',
        'status',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'agent_roles', 'role_id', 'user_id');
    }

}
