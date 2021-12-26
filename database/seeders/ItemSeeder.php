<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Wallet::all() as $wallet){
            Item::factory(2)->create([
                'wallet_id' => $wallet
            ]);
        }
    }
}
