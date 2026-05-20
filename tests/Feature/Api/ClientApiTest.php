<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Client;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_clients_requires_token()
    {
        $response = $this->getJson('/api/clients');
        $response->assertStatus(401);
    }

    public function test_api_clients_index_returns_clients_with_token()
    {
        // ensure API_TOKEN is available to middleware
        putenv('API_TOKEN=testtoken');
        $_ENV['API_TOKEN'] = 'testtoken';
        $_SERVER['API_TOKEN'] = 'testtoken';

        Client::create([
            'name' => 'Cliente Uno',
            'email' => 'uno@example.com',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer testtoken')
                         ->getJson('/api/clients');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }
}
