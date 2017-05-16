<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class MetatagPolicy
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

    public function index(User $user) {
        return in_array($user->group->name, ['admin', 'moderator']);
    }

    public function add(User $user) {
        return $user->group->name == 'admin';
    }

    public function delete(User $user) {
        return $user->group->name == 'admin';
    }

    public function route(User $user) {
        return $user->group->name == 'admin';
    }
}
