<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_blocked(): void
    {
        $user = User::factory()->create(['is_blocked' => false]);
        
        $this->assertFalse($user->is_blocked);
        
        $user->is_blocked = true;
        $user->save();
        
        $this->assertTrue($user->fresh()->is_blocked);
    }

    public function test_user_can_be_unblocked(): void
    {
        $user = User::factory()->create(['is_blocked' => true]);
        
        $this->assertTrue($user->is_blocked);
        
        $user->is_blocked = false;
        $user->save();
        
        $this->assertFalse($user->fresh()->is_blocked);
    }

    public function test_user_has_role_relationship(): void
    {
        $role = Role::factory()->create(['name' => 'patient']);
        $user = User::factory()->create(['role_id' => $role->id]);
        
        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertEquals('patient', $user->role->name);
    }
}
