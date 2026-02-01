<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

use Illuminate\Support\Str;

class SupportTicket extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'protocol',
    ];


    protected static function booted(): void
    {
        static::creating(function ($ticket) {
            if (!$ticket->protocol) {
                $nanoId = Str::lower(Str::random(8));
                $ticket->protocol = 'EV-' . $nanoId . '-' . time();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'protocol';
    }

    public function replies()
    {
        return $this->hasMany(SupportTicketReply::class, 'support_ticket_id');
    }
}
