<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Streak extends Model implements Auditable
{
    use AuditableTrait;
    protected $fillable = ['goal_id'];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
