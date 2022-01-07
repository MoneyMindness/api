<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Http\Resources\WalletResource;
use App\Models\Item;
use App\Models\Wallet;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ItemController extends Controller
{
    use ApiResponseHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->respondError("Cannot list the items individually. must be listed from wallet endpoint with the wallet");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return $this->respondError("Please use the POST api route to directly create a new item on the wallet");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = $request->validate([
            'wallet_id' => ['required', 'numeric'],
            'title' => ['required', 'min:1', 'max:100'],
            'description' => ['required', 'min:1', 'max:255']
        ]);


        $wallet = Wallet::find((int)$validator['wallet_id']);

        $gate = Gate::inspect('create', [Item::class, $wallet]);

        if ($gate->allowed()) {
            $item = Item::create([
                'user_id' => $request->user()->id,
                'wallet_id' => (int)$validator['wallet_id'],
                'title' => $validator['title'],
                'description' => $validator['description']
            ]);

            return $this->respondWithSuccess((new ItemResource($item)));
        }

        return $this->respondForbidden($gate->message());

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $item = Item::with(['budgets', 'user'])->where('id', $id)->first();

        $gate = Gate::inspect('view', $item);

        if ($gate->allowed()) {
            return $this->respondWithSuccess((new ItemResource($item)));
        }

        return $this->respondForbidden($gate->message());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return JsonResponse
     */
    public function edit(): JsonResponse
    {
        return $this->respondError("Please use the POST api route for updating item");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $item = Item::find($id);

        // Forgets the wallet id. so it cannot be moved to another wallet.
        // Very jank but big brain
        $collection = collect($request->all())
            ->filter()
            ->forget('wallet_id')
            ->toArray();


        $gate = Gate::inspect('update', $item);

        if ($gate->allowed()) {
            $item->update($collection);
            return $this->respondOk("Item updated successfully!");
        }

        return $this->respondForbidden($gate->message());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $item = Item::find($id);

        $gate = Gate::inspect('delete', $item);

        if($gate->allowed()){
            $item->delete();
            return $this->respondOk("Item deleted successfully!");
        }

        return $this->respondForbidden($gate->message());
    }
}
