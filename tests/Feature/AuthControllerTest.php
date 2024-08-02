<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_manager(): void
    {
        $response = $this->postJson('/api/v1/register', [
                'name' => 'John Doe',
                'email' => 'aXUeh@example.com',
                'password' => Hash::make('password'),
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email'],
                'access_token',
                'token_type'
            ]);;

        $this->assertDatabaseHas('users', [
            'email' => 'aXUeh@example.com',
            'role_id' => Role::where('name', 'manager')->first()->id
        ]);
    }

    public function test_user_can_login_as_employee()
    {
        $user = User::factory()->employee()->create();
        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'role_id'],
                'access_token',
                'token_type'
            ]);
    }

    public function test_user_can_login_as_manager()
    {
        $user = User::factory()->manager()->create(
            ['password' => Hash::make('password123')]
        );
        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'role_id'],
                'access_token',
                'token_type'
            ]);
    }

    public function test_manager_can_logout()
    {
        $user = User::factory()->manager()->create();
        $response = $this->postJson('/api/v1/logout', [], [
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->plainTextToken
        ]);
        $response->assertStatus(200);
    }

    public function test_employee_can_logout()
    {
        $user = User::factory()->employee()->create();
        $response = $this->postJson('/api/v1/logout', [], [
            'Authorization' => 'Bearer ' . $user->createToken('auth_token')->plainTextToken
        ]);
        $response->assertStatus(200);
    }
}
