<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SupportTicket;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class SupportTicketReply extends Model implements Auditable
{
    use AuditableTrait;
    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'message',
        'is_admin',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
