<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WalletController extends Controller
{
    use ApiResponseHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->respondWithSuccess(
            $request->user()->wallets()->get(['id', 'title', 'description'])
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create() : JsonResponse
    {
       return $this->respondError("Please use the API Route for creating wallets");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => ['required', 'min:1', 'max:100'],
            'description' => ['required', 'min:1', 'max:255']
        ]);

        if (
            $request->user()->tokenCan('create:wallet') ||
            $request->user()->tokenCan('full_access')) {

            $wallet = $request->user()->wallets()->create([
                'title' => $request->get('title'),
                'description' => $request->get('description')
            ]);

            return $this->respondWithSuccess([
                "message" => "Wallet created",
                "data" => $wallet
            ]);
        }

        return $this->respondError("Server error");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $wallet = Wallet::with('walletItem')->where('id', $id)->first();

        if(!$request->user()->tokenCan('show:wallet') || !$request->user()->tokenCan('full_access'))
            return $this->respondForbidden("Token is not authorized to do that");

        if($wallet->user_id == $request->user()->id){
            return $this->respondWithSuccess([
                'id' => $wallet->id,
                'title' => $wallet->title,
                'description' => $wallet->description,
                'items' => $wallet->walletItem->map(function ($item){
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description
                    ];
                })
            ]);
        }else{
            return $this->respondForbidden("This wallet is not yours");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id): JsonResponse
    {
        return $this->respondError("Please use the API Route for editing wallets");
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
        $wallet = Wallet::find($id);

        if(!$request->user()->tokenCan('update:wallet') || !$request->user()->tokenCan('full_access'))
            return $this->respondForbidden("Token is not authorized to do that");

        if($wallet->user_id === $request->user()->id){
           $wallet->update(array_filter($request->all()));
           return $this->respondWithSuccess("Wallet updated successfully!");
        }else{
            return $this->respondForbidden("Not your wallet ID!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
