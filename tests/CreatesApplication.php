<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
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
