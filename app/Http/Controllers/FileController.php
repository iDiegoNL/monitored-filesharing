<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Auth\Access\AuthorizationException;

class FileController extends Controller
{
    /**
     * Get the file.
     *
     * @throws AuthorizationException
     */
    public function accessFile(string $shareKey)
    {
        $file = File::query()->whereShareKey($shareKey)->firstOrFail();

        $this->authorize('download', $file);

        return $file->path;
    }
}
