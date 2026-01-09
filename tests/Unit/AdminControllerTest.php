<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private $adminRole;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminRole = Role::factory()->admin()->create();
    }

    public function test_admin_can_block_user(): void
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        
        $user = User::factory()->create(['is_blocked' => false]);
        
        $this->actingAs($admin)
            ->post(route('admin.user.toggle-block', $user->id))
            ->assertRedirect();
        
        $this->assertTrue($user->fresh()->is_blocked);
    }

    public function test_admin_can_unblock_user(): void
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        
        $user = User::factory()->create(['is_blocked' => true]);
        
        $this->actingAs($admin)
            ->post(route('admin.user.toggle-block', $user->id))
            ->assertRedirect();
        
        $this->assertFalse($user->fresh()->is_blocked);
    }

    public function test_admin_cannot_block_another_admin(): void
    {
        $admin1 = User::factory()->create(['role_id' => $this->adminRole->id]);
        $admin2 = User::factory()->create(['role_id' => $this->adminRole->id]);
        
        $this->actingAs($admin1)
            ->post(route('admin.user.toggle-block', $admin2->id))
            ->assertRedirect();
        
        $this->assertFalse($admin2->fresh()->is_blocked);
    }
}
