<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WalletPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool|Response
     */
    public function viewAny(User $user): bool | Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return Response|bool
     */
    public function view(User $user, Wallet $wallet): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('show:wallet')) {
            return $wallet->user_id === $user->id
                ? Response::allow()
                : Response::deny('This wallet does not belong to the user');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('create:wallet')) {
            return Response::allow();
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return Response|bool
     */
    public function update(User $user, Wallet $wallet)
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('update:wallet')) {
            return $wallet->user_id === $user->id
                ? Response::allow()
                : Response::deny('This wallet does not belong to the user');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return Response|bool
     */
    public function delete(User $user, Wallet $wallet): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('delete:wallet')) {
            return $wallet->user_id === $user->id
                ? Response::allow()
                : Response::deny('This wallet does not belong to the user');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return Response|bool
     */
    public function restore(User $user, Wallet $wallet)
    {
        // not needed since it's hard deleted
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return Response|bool
     */
    public function forceDelete(User $user, Wallet $wallet)
    {
        // not needed since it's hard deleted
    }
}
