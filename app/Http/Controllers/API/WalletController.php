<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class WalletController extends Controller
{
    use ApiResponseHelpers;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->respondWithSuccess([
            "message" => "User wallets successfully retrieved",
            "data" => WalletResource::collection($request->user()->wallets)
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return $this->respondError("Please use the API Route for creating wallets");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => ['required', 'min:1', 'max:100'],
            'description' => ['required', 'min:1', 'max:255']
        ]);

        $gate = Gate::inspect('create', Wallet::class);

        if ($gate->allowed()) {

            $wallet = $request->user()->wallets()->create([
                'title' => $request->get('title'),
                'description' => $request->get('description')
            ]);

            return $this->respondWithSuccess([
                "message" => "Wallet created",
                "data" => (new WalletResource($wallet))
            ]);
        }

        return $this->respondForbidden($gate->message());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $wallet = Wallet::with('walletItem')->where('id', $id)->first();

        $gate = Gate::inspect('view', $wallet);

        if ($gate->allowed()) {

            return $this->respondWithSuccess([
                "message" => "Wallet information successfully retrieved",
                "data" => (new WalletResource($wallet))
            ]);

        }

        return $this->respondForbidden($gate->message());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        return $this->respondError("Please use the API Route for editing wallets");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $wallet = Wallet::find($id);

        $gate = Gate::authorize('update', $wallet);

        if ($gate->allowed()) {
            $wallet->update(array_filter($request->all()));
            return $this->respondOk("Wallet updated successfully!");
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
        $wallet = Wallet::find($id);

        $gate = Gate::authorize('delete', $wallet);

        if ($gate->allowed()) {
            $wallet->delete();
            return $this->respondOk("Wallet deleted successfully!");
        }

        return $this->respondForbidden($gate->message());
    }
}
