<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_requires_authentication(): void
    {
        $response = $this->get('/repository');

        $response->assertRedirect('/login');
    }

    public function test_repository_index_renders_for_authenticated_user(): void
    {
        Storage::fake('repository');

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/repository');

        $response->assertOk();
        $response->assertSee('Repository');
    }
}
