<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class BlockedUserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_blocked_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'blocked@test.com',
            'password' => Hash::make('password'),
            'is_blocked' => true
        ]);
        
        $response = $this->post('/login', [
            'email' => 'blocked@test.com',
            'password' => 'password'
        ]);
        
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_unblocked_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'active@test.com',
            'password' => Hash::make('password'),
            'is_blocked' => false
        ]);
        
        $response = $this->post('/login', [
            'email' => 'active@test.com',
            'password' => 'password'
        ]);
        
        $this->assertAuthenticated();
    }

    public function test_user_is_logged_out_when_blocked(): void
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
            'is_blocked' => false
        ]);
        
        // Zaloguj użytkownika
        $this->actingAs($user);
        $this->assertAuthenticated();
        
        // Zablokuj użytkownika
        $user->is_blocked = true;
        $user->save();
        
        // Próba ponownego logowania
        $this->post('/logout');
        
        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'password'
        ]);
        
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
