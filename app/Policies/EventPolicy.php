<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if(Auth::user() === null){
            return false;
        }

        return true;
    }

    public function view(User $user, Event $event): bool
    {
        if(Auth::user() === null){
            return false;
        }

        return true;
    }

    public function create(User $user): bool
    {
        if(Auth::user() === null){
            return false;
        }

        return true;
    }

    public function update(User $user, Event $event): bool
    {
        if(Auth::user() === null || ( $event->user_id !== $user->id && !$user->hasAnyRole(["admin","support"]) )){
            return false;
        }

        return true;
    }

    public function delete(User $user, Event $event): bool
    {
        if(Auth::user() === null || ( $event->user_id !== $user->id && !$user->hasAnyRole(["admin","support"]) )){
            return false;
        }

        return true;
    }

    public function restore(User $user, Event $event): bool
    {
        if(Auth::user() === null || ( $event->user_id !== $user->id && !$user->hasAnyRole(["admin","support"]) )){
            return false;
        }

        return true;
    }

    public function forceDelete(User $user, Event $event): bool
    {
        if(Auth::user() === null ||
            ( $event->user_id !== $user->id &&
                !$user->hasAnyRole(["admin","support"])
            )
        ){
            return false;
        }

        return true;
    }
}
