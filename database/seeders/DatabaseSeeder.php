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
            ->roles()->attach([Role::ROLE_ADMIN, Role::ROLE_RESIDENT]);

        User::factory()
            ->create([
                'name' => 'Pothole fairy',
                'email' => 'fairy@example.com',
            ])
            ->roles()->attach([Role::ROLE_SUPPLIER]);

        User::factory()
            ->create()
            ->roles()->attach(Role::ROLE_RESIDENT);

        User::factory()
            ->create()
            ->roles()->attach(Role::ROLE_RESIDENT);

        $this->seedProperties();
    }

    public function seedRoles(): array
    {
        return [
            Role::create([ 'id' => Role::ROLE_ADMIN , 'name' => 'Admin']),
            Role::create([ 'id' => Role::ROLE_RESIDENT , 'name' => 'Resident']),
            Role::create([ 'id' => Role::ROLE_SUPPLIER, 'name' => 'Supplier' ])
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
