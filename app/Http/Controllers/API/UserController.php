<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponseHelpers;

    public function index(Request $request)
    {
        return $this->respondWithSuccess(
            $this->formatUser($request->user())
        );
    }

    public function withWallets(Request $request)
    {
        return $this->respondWithSuccess(
            [
                "user" => $this->formatUser($request->user()),
                "wallets" => Wallet::where('user_id', $request->user()->id)->get()->map(function ($i) {
                    return [
                        "id" => $i->id,
                        "title" => $i->title,
                        "description" => $i->description
                    ];
                })
            ]
        );
    }

    private function formatUser($user)
    {
        return [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email
        ];
    }
}
