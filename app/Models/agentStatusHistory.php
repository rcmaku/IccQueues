<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AgentStatusHistory extends Model
{
    protected $table = 'agent_status_history'; // Explicitly define the table name

    // Explicitly specify which columns are mass assignable
    protected $fillable = [
        'user_id',
        'agent_status_id',
        'changed_at',  // Ensure this is included
    ];

    protected $dates = ['changed_at'];

    // Relationships (if any)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agentStatus()
    {
        return $this->belongsTo(AgentStatus::class);
    }
}

