<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DmLogic\CommunityContributions\Models\Role;
use DmLogic\CommunityContributions\Models\User;
use DmLogic\CommunityContributions\Models\Property;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = $this->seedRoles();
        $properties = $this->seedProperties();

        User::factory()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'property_id' => $properties[0]->id
            ])
            ->roles()->attach(1);

        User::factory()
            ->create([
                'name' => 'Pothole fairy',
                'email' => 'fairy@example.com',
                'property_id' => $properties[1]->id
            ])
            ->roles()->attach([2,3]);

            User::factory()
            ->create([
                'property_id' => $properties[2]->id
            ])
            ->roles()->attach(3);

            User::factory()
            ->create([
                'property_id' => $properties[3]->id
            ])
            ->roles()->attach(3);
    }

    public function seedRoles(): array
    {
        return [
            Role::create([ 'name' => 'Admin' ]),
            Role::create([ 'name' => 'Resident' ]),
            Role::create([ 'name' => 'Maintainer' ])
        ];
    }

    public function seedProperties(): Collection
    {
        return Property::factory()
                ->count(4)
                ->state(new Sequence(
                    ['number' => '1'],
                    ['number' => '10'],
                    ['number' => '10a'],
                    ['number' => '20'],
                ))
                ->create();
    }
}
