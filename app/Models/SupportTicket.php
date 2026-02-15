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

    protected $appends = ['created_at_formatted', 'status_label', 'status_color'];


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

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendente',
            'in_progress' => 'Em Andamento',
            'resolved' => 'Resolvido',
            'closed' => 'Fechado',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
