<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class FeatureTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function adminUser(): User
    {
        return User::find(1);
    }

    public function supplierUser(): User
    {
        return User::find(2);
    }
}
