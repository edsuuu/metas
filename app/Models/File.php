<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class File extends Model
{
    protected $fillable = [
        'uuid',
        'fileable_id',
        'fileable_type',
        'path',
        'disk',
        'filename',
        'mime_type',
        'size',
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
