<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class agentStatus extends Model
{
    protected $table = 'agent_status'; // Set the table to 'agent_status'

    // Allow mass assignment for the 'name' field
    protected $fillable = ['name']; // Add 'name' here to allow mass assignment


    public function users()
    {
        return $this->hasMany(User::class);
    }

    // AgentStatus model
    public function agentStatusHistory()
    {
        return $this->hasMany(AgentStatusHistory::class);
    }



}
