<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Tutor;
class TutorPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // app/Policies/TutorPolicy.php

public function update(User $user, Tutor $tutor)
{
    return $user->id === $tutor->user_id;
}

}
