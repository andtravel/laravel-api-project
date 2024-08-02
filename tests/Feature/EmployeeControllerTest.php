<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_create_employee(): void
    {
        $manager = User::factory()->manager()->create();

        $response = $this->actingAs($manager)->postJson('/api/v1/employees', [
            'name' => 'John Doe',
            'email' => 'aXUeh@example.com',
            'password' => 'password',
            'role_id' => Role::where('name', 'employee')->first()->id
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'aXUeh@example.com',
            'manager_id' => $manager->id
        ]);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'employee' => ['id', 'name', 'email', 'role_id', 'manager_id']
        ]);
    }

    public function test_employee_can_not_create_employee()
    {
        $employee = User::factory()->employee()->create();

        $response = $this->actingAs($employee)->postJson('/api/v1/employees', [
            'name' => 'John Doe',
            'email' => 'aXUeh@example.com',
            'password' => 'password',
            'role_id' => Role::where('name', 'employee')->first()->id
        ]);
        $response->assertForbidden();
    }
}
