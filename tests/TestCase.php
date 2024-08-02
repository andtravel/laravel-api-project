<?php

namespace Tests;

use App\Models\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'employee']);
    }
}
