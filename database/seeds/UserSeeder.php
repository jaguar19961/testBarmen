<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            User::truncate();

            User::create([
                'name' => 'Barmen',
                'email' => 'barmen@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('secret'),
                'remember_token' => Str::random(10),
            ]);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
