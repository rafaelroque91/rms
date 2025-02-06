<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index()
    {
        Resource::factory()->count(3)->create();

        $response = $this->getJson('/api/resources');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_store()
    {
        $data = [
            'name' => 'Test Resource',
            'description' => 'Test Description',
        ];

        $response = $this->postJson('/api/resources', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);
    }

    public function test_show()
    {
        $resource = Resource::factory()->create();

        $response = $this->getJson("/api/resources/{$resource->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $resource->id]);
    }

    public function test_update()
    {
        $resource = Resource::factory()->create();

        $data = [
            'name' => 'Updated Resource',
            'description' => 'Updated Description',
        ];

        $response = $this->putJson("/api/resources/{$resource->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_destroy()
    {
        $resource = Resource::factory()->create();

        $response = $this->deleteJson("/api/resources/{$resource->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('resources', ['id' => $resource->id]);
    }
}
