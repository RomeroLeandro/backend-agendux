<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Service $service): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Service $service): boo
    {
        return $user->id === $service->user_id;
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->id === $service->user_id;
    }
}
