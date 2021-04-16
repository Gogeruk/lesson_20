<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LABEL;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LabelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given Label can be updated by the User.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Label  $label
     * @return bool
     */
    public function delete(User $user, Label $label)
    {
        return $user->id === $label->user_id
                    ? Response::allow()
                    : Response::deny('Go away!');
    }
}
