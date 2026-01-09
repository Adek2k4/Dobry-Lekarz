<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    // Create patient role
    $patientRole = \App\Models\Role::factory()->patient()->create();
    
    $response = $this->post('/register', [
        'name' => 'Test User',
        'surname' => 'Test Surname',
        'phone' => '123456789',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role_id' => $patientRole->id,
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/');
});
