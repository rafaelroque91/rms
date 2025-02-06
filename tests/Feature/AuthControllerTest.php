<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User registered successfully']);
    }

    public function test_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'data' => ['access_token', 'token_type']]);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }
}
