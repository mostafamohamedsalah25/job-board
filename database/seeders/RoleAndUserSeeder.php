<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Employer;
use App\Models\Profile;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $employerRole = Role::create(['name' => 'employer']);
        $candidateRole = Role::create(['name' => 'candidate']);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@jobboard.com',
            'password' => bcrypt('password')
        ]);
        $admin->assignRole($adminRole);

        User::factory(10)->create()->each(function ($user) use ($employerRole) {
            $user->assignRole($employerRole);
            Employer::create([
                'user_id' => $user->id,
                'company_name' => $user->name . ' Company',
            ]);
        });

        User::factory(10)->create()->each(function ($user) use ($candidateRole) {
            $user->assignRole($candidateRole);
            Profile::create([
                'user_id' => $user->id,
                'bio' => 'This is a bio for ' . $user->name,
            ]);
        });
    }
}
