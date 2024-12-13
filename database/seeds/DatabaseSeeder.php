<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    // public function run()
    // {
    //     // $this->call(UserSeeder::class);
    // }
    public function run()
    {
        // Seed roles
        $roles = [
            ['role' => 'admin'],
            ['role' => 'manager'],
            ['role' => 'standard'],
        ];

        foreach ($roles as $role) {
            $existingRole = User::where('role', $role['role'])->first();
            if (!$existingRole) {
                User::create($role);
            }
        }

        // Assign roles to users
        $users = User::all();
        foreach ($users as $user) {
            if ($user->email == 'paul@gmail.com') {
                $user->role = 'admin';
            } elseif ($user->email == 'arya@yopmail.com') {
                $user->role = 'manager';
            } else {
                $user->role = 'standard';
            }
            $user->save();
        }
    }
}
