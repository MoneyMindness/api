<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BudgetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Budget $budget): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('view:budget')) {
            return $budget->user()->is($user)
                ? Response::allow()
                : Response::deny('Cannot view this budget since it doesnt belong to the user!');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @param Item $item
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Item $item): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('create:budget')) {
            return $item->user()->is($user)
                ? Response::allow()
                : Response::deny('Cannot create budget in this item since it doesnt belong to the user!');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Budget $budget): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('update:budget')) {
            return $budget->user()->is($user)
                ? Response::allow()
                : Response::deny('Cannot update this budget since it doesnt belong to the user!');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Budget $budget): Response|bool
    {
        if ($user->tokenCan('full_access') || $user->tokenCan('delete:budget')) {
            return $budget->user()->is($user)
                ? Response::allow()
                : Response::deny('Cannot delete this budget since it doesnt belong to the user!');
        }

        return Response::deny('Insufficient permission!');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Budget $budget)
    {

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Budget $budget)
    {
        //
    }
}
