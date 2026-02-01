<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Streak extends Model
{
    protected $fillable = ['goal_id'];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
