<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', env('ADMIN_EMAIL', 'admin@rent.use'))->first()
            ?? new User();

        $email    = env('ADMIN_EMAIL', 'admin@rent.use');
        $password = env('ADMIN_PASSWORD', 'password');
        $phone    = env('ADMIN_PHONE', '+00000000000');

        $admin->forceFill([
            'name'     => 'Admin',
            'email'    => $email,
            'phone'    => $phone,
            'password' => Hash::make($password),
            'is_admin' => true,
        ])->save();

        $this->command->info("Admin ready: {$email} / {$password}");
    }
}
