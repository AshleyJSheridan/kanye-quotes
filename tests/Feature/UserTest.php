<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRegisterUserEndpoint()
    {
        $response = $this->postJson('/api/user/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    public function testRegisterUserInvalidInputEndpoint()
    {
        $response = $this->postJson('/api/user/register', []);

        $response->assertStatus(422);
    }

    public function testRegisterWithExistingEmail()
    {
        $user = User::factory()->create([
            'email' => 'existinguser@example.com',
        ]);

        $response = $this->postJson('/api/user/register', [
            'name' => 'Test User',
            'email' => 'existinguser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['email']);
        $response->assertJsonFragment(['The email has already been taken.']);
    }

    public function testLogin()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password123'),
        ]);

        $response = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'token',
        ]);
    }

    public function testLoginWithInvalidCredentials()
    {
        $response = $this->postJson('/api/user/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'invalidpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }

    public function testLogout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/user/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Logout successful',
        ]);
    }

    public function testLogoutWithoutToken()
    {
        $response = $this->postJson('/api/user/logout');

        $response->assertStatus(401);
    }
}
