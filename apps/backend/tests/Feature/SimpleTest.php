<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class SimpleTest extends TestCase
{
    use RefreshDatabase;

    public function test_basic_assertion()
    {
        $this->assertTrue(true);
    }

    public function test_db_connection()
    {
        $user = User::factory()->create();
        $this->assertDatabaseCount('users', 1);
    }
}
