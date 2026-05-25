<?php

namespace Database\Seeders;

use App\Enum\RoleName;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles first
        $adminRole = Role::create(['name' => RoleName::Admin->value, 'label' => 'Administrator']);
        $userRole  = Role::create(['name' => RoleName::User->value, 'label' => 'Regular User']);

        // Create categories
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Travel',     'slug' => 'travel'],
            ['name' => 'Food',       'slug' => 'food'],
            ['name' => 'Lifestyle',  'slug' => 'lifestyle'],
            ['name' => 'Business',   'slug' => 'business'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create permissions
        $permissions = [
            ['name' => 'View Posts',   'route_name' => 'posts.index'],
            ['name' => 'Show Post',    'route_name' => 'posts.show'],
            ['name' => 'Create Post',  'route_name' => 'posts.create'],
            ['name' => 'Store Post',   'route_name' => 'posts.store'],
            ['name' => 'Edit Post',    'route_name' => 'posts.edit'],
            ['name' => 'Update Post',  'route_name' => 'posts.update'],
            ['name' => 'Delete Post',  'route_name' => 'posts.destroy'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Give admin role all permissions
        $adminRole->permissions()->attach(Permission::all());

        // Give user role all permissions (handled in post p
        $userRole->permissions()->attach(Permission::all());

        // Create 3 admin users
        User::factory(3)->create()->each(function ($user) use ($adminRole) {
            $user->roles()->attach($adminRole);
            $user->posts()->createMany(
                \Database\Factories\PostFactory::new()->count(rand(2, 5))->make()->toArray()
            )->each(function ($post) {
                $post->status()->create(['value' => 'published']);
            });
        });

        // Create 10 regular users
        User::factory(10)->create()->each(function ($user) use ($userRole) {
            $user->roles()->attach($userRole);
            $user->posts()->createMany(
                \Database\Factories\PostFactory::new()->count(rand(2, 5))->make()->toArray()
            )->each(function ($post) {
                $post->status()->create(['value' => 'published']);
            });
        });
    }
}
