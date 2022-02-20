<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Vinkla\Hashids\Facades\Hashids;

trait HasShareIdTrait
{
    protected static function booted(): void
    {
        // When a file is created, generate a share_uuid
        static::creating(function ($file) {
            $file->share_id = self::generateShareId();
        });
    }

    public function getShareKey(): string
    {
        return Hashids::encode((int)$this->share_id);
    }

    public static function generateShareId(): int
    {
        // Generate a unique ID
        $uniqid = uniqid('', true);

        // Strip everything but numbers, and return it
        return (int)preg_replace('~\D~', '', $uniqid);
    }

    public function scopeWhereShareKey(Builder $query, string $shareKey): Builder
    {
        return $query->where('share_id', Hashids::decode($shareKey));
    }
}
