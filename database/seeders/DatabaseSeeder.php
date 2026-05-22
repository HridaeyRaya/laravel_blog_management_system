<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles first
        $adminRole = Role::create(['name' => 'admin', 'label' => 'Administrator']);
        $userRole  = Role::create(['name' => 'user',  'label' => 'Regular User']);

        // Create 3 admin users
        User::factory(3)->create()->each(function ($user) use ($adminRole) {
            $user->roles()->attach($adminRole);
            $user->posts()->createMany(
                \Database\Factories\PostFactory::new()->count(rand(2, 5))->make()->toArray()
            )->each(function ($post) {
                $post->status()->create(['value' => 'published']); // ← add status
            });
        });

        // Create 10 regular users
        User::factory(10)->create()->each(function ($user) use ($userRole) {
            $user->roles()->attach($userRole);
            $user->posts()->createMany(
                \Database\Factories\PostFactory::new()->count(rand(2, 5))->make()->toArray()
            )->each(function ($post) {
                $post->status()->create(['value' => 'published']); // ← add status
            });
        });
    }
}
