<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can download the file.
     *
     * @param User $user
     * @param File $file
     * @return Response
     */
    public function download(User $user, File $file): Response
    {
        // If the user is an admin, allow access
        if ($user->is_admin) {
            return Response::allow();
        }

        // If the user has verified their email, allow access
        // Only users with @truckersmp.com can create accounts with emails
        if ($user->hasVerifiedEmail()) {
            return Response::allow();
        }

        // If the user's TruckersMP group ID is in the allowed group IDs, allow access
        if (in_array($user->getTruckersMpAccount()['groupID'], $file->permitted_group_ids, false)) {
            return Response::allow();
        }

        // Otherwise, deny access
        return Response::deny('You do not have permission to view this file.');
    }
}
