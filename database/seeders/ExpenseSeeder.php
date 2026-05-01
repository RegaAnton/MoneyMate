<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@moneymate.com')->first();
        $categories = Category::all();

        if (!$user || $categories->isEmpty()) {
            return;
        }

        $startDate = Carbon::create(2026, 5, 1);
        $endDate = Carbon::create(2026, 5, 31);

        while ($startDate->lte($endDate)) {
            // Create at least 1-3 expenses per day
            $count = rand(1, 3);

            for ($i = 0; $i < $count; $i++) {
                Expense::factory()->create([
                    'user_id' => $user->id,
                    'category_id' => $categories->random()->id,
                    'date' => $startDate->toDateString(),
                    'amount' => rand(10000, 500000),
                ]);
            }

            $startDate->addDay();
        }
    }
}
