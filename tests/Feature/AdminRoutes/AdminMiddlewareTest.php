<?php

declare(strict_types=1);

namespace Tests\Feature\AdminRoutes;

use App\Models\User;
use Tests\TestCase;

final class AdminMiddlewareTest extends TestCase
{
    public function testNonAdminUserCannotAccess(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/admin/orders');
        $response->assertStatus(403);
        $response->assertJson(['error' => 'Доступ запрещен']);
    }
}
