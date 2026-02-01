<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Experience extends Model implements Auditable
{
    use AuditableTrait;

    protected $fillable = ['user_id', 'amount', 'description', 'source', 'goal_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
