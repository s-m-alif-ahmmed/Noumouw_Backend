<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);

        $user->profile()->create([
            'user_id' => $user->id,
            'birth_date' => '1990-01-01',
            'parent_role' => 'father',
            'is_parent' => 1,
            'country' => 'Bangladesh'   
        ]);

        $user->children()->createMany([
            [
                'user_id' => $user->id,
                'name' => 'Child 1',
                'birth_date' => '2010-01-01',
            ],
            [
                'user_id' => $user->id,
                'name' => 'Child 2',
                'birth_date' => '2012-01-01',
            ]
            ]);

        $this->call(TagsSeeder::class);
        // $this->call(CourseSeeder::class);

    }
}
