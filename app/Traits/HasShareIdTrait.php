<?php

namespace App\Traits;

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
}
