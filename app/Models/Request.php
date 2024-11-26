<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'channel',
        'start_time',
        'end_time',
        'user_id',
    ];

    // Define relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
