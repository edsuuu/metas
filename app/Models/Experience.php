<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Experience extends Model
{
    protected $fillable = ['user_id', 'amount', 'description', 'source', 'goal_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
