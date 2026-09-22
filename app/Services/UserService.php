<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Role assignment is *not* handled here — the `roles` field on
 * UserResource uses Filament's native `->relationship()` sync, which
 * commits directly to the model_has_roles pivot after save. That field is
 * additionally gated by `->visible()`/`->dehydrated()` against the
 * `assignRoles` policy ability, so an Administrator without
 * `manage_roles` never has the field rendered *or* processed — the
 * permission check happens before Filament's sync ever runs, not after.
 */
class UserService
{
    public function __construct(protected UserRepositoryInterface $users) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): User
    {
        $attributes['password'] = Hash::make($attributes['password']);

        return $this->users->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(User $user, array $attributes): User
    {
        if (empty($attributes['password'])) {
            unset($attributes['password']);
        } else {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        return $this->users->update($user, $attributes);
    }

    public function delete(User $user): bool
    {
        return $this->users->delete($user);
    }
}
