<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = $this->seedRoles();

        User::factory()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ])
            ->roles()->attach(1);

        User::factory()
            ->create([
                'name' => 'Pothole fairy',
                'email' => 'fairy@example.com',
            ])
            ->roles()->attach([2,3]);

        User::factory()
            ->create()
            ->roles()->attach(3);

        User::factory()
            ->create()
            ->roles()->attach(3);

        $this->seedProperties();
    }

    public function seedRoles(): array
    {
        return [
            Role::create([ 'name' => Role::ROLE_ADMIN ]),
            Role::create([ 'name' => Role::ROLE_RESIDENT ]),
            Role::create([ 'name' => Role::ROLE_MAINTAINER ])
        ];
    }

    public function seedProperties(): Collection
    {
        return Property::factory()
                ->count(4)
                ->state(new Sequence(
                    ['number' => '1', 'user_id' => 1],
                    ['number' => '10', 'user_id' => 2],
                    ['number' => '10a', 'user_id' => 3],
                    ['number' => '20', 'user_id' => 4],
                ))
                ->create();
    }
}
