<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Item;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Item::all() as $item) {
            Budget::factory(10)->create([
                'item_id' => $item,
                'user_id' => $item->user_id
            ]);
        }
    }
}
