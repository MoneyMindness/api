<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BudgetResource;
use App\Models\Budget;
use App\Models\Item;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BudgetController extends Controller
{
    use ApiResponseHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->respondForbidden("Cannot list the budgets individually. must be listed from item endpoint with the item");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return $this->respondError("Please use the POST route to create the budget!");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = $request->validate([
            'item_id' => ['required', 'numeric'],
            'title' => ['required', 'min:1', 'max:50'],
            'description' => ['required', 'min:1', 'max:100'],
            'amount' => ['required', 'numeric']
        ]);

        $item = Item::find((int)$validator['item_id']);

        $gate = Gate::inspect('create', [Budget::class, $item]);

        if ($gate->allowed()) {
            $budget = Budget::create([
                'item_id' => $validator['item_id'],
                'user_id' => $request->user()->id,
                'title' => $validator['title'],
                'description' => $validator['description'],
                'amount' => (double)$validator['amount'],
            ]);

            return $this->respondWithSuccess((new BudgetResource($budget)));
        }

        return $this->respondForbidden($gate->message());
    }

    /**
     * Display the specified resource.
     *
     * @param Budget $budget
     * @return JsonResponse
     */
    public function show(Budget $budget): JsonResponse
    {
        $gate = Gate::inspect('view', $budget);

        if ($gate->allowed()) {
            return $this->respondWithSuccess((new BudgetResource($budget)));
        }

        return $this->respondForbidden($gate->message());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Budget $budget
     * @return JsonResponse
     */
    public function edit(Budget $budget): JsonResponse
    {
        return $this->respondError("Please use the POST route to update the budget!");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Budget $budget
     * @return JsonResponse
     */
    public function update(Request $request, Budget $budget): JsonResponse
    {
        $gate = Gate::inspect('update', $budget);

        if ($gate->allowed()) {
            $collection = collect($request->all())
                ->filter()
                ->forget('wallet_id')
                ->forget('item_id')
                ->toArray();

            $budget->update($collection);
            return $this->respondOk("Budget updated successfully!");
        }

        return $this->respondForbidden($gate->message());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Budget $budget
     * @return JsonResponse
     */
    public function destroy(Budget $budget): JsonResponse
    {
        $gate = Gate::authorize('delete', $budget);

        if($gate->allowed()){
            $budget->delete();
            return $this->respondOk("Budget successfully deleted!");
        }

        return $this->respondForbidden($gate->message());
    }
}
