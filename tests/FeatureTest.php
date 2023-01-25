<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class FeatureTest extends TestCase
{
    use CreatesApplication, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
}
