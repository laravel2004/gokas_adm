<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\SettingLimit;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Position::create([
            'name' => 'Operator / Sr operator',
        ]);

        Position::create([
            'name' => 'Supervisor',
        ]);

        Position::create([
            'name' => 'Shift Chief',
        ]);

        Position::create([
            'name' => 'Section Head up',
        ]);

        SettingLimit::create([
            'position' => 'Operator / Sr operator',
            'limit_paylater' => 1000000,
            'limit_loan' => 2000000,
            'limit_credit' => 2500000,
        ]);

        SettingLimit::create([
            'position' => 'Supervisor',
            'limit_paylater' => 1000000,
            'limit_loan' => 2500000,
            'limit_credit' => 3000000,
        ]);

        SettingLimit::create([
            'position' => 'Shift Chief',
            'limit_paylater' => 1000000,
            'limit_loan' => 3000000,
            'limit_credit' => 3500000,
        ]);

        SettingLimit::create([
            'position' => 'Section Head Up',
            'limit_paylater' => 1000000,
            'limit_loan' => 3500000,
            'limit_credit' => 4000000,
        ]);
    }
}
