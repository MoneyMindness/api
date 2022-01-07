<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Item $item
     * @return Response|bool
     */
    public function view(User $user, Item $item): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('view:item')) {
            return $item->user()->is($user)
                ? Response::allow()
                : Response::deny('This item does not belong to the user!');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Wallet $wallet
     * @return Response|bool
     */
    public function create(User $user, Wallet $wallet): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('create:item')) {
            return $wallet->user()->is($user)
                ? Response::allow()
                : Response::deny('Cannot create item in this wallet since it doesnt belong to the user!');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Item $item
     * @return Response|bool
     */
    public function update(User $user, Item $item): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('update:item')) {
            return $item->user()->is($user)
                ? Response::allow()
                : Response::deny('Cannot update this item since it doesnt belong to the user!');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Item $item
     * @return Response|bool
     */
    public function delete(User $user, Item $item)
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('delete:item')) {
            return $item->user()->is($user)
                ? Response::allow()
                : Response::deny('Cannot delete this item since it doesnt belong to the user!');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Item $item
     * @return Response|bool
     */
    public function restore(User $user, Item $item)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Item $item
     * @return Response|bool
     */
    public function forceDelete(User $user, Item $item)
    {
        //
    }
}
