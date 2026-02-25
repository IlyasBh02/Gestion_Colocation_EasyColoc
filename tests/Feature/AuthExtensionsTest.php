<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthExtensionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_registered_user_becomes_admin(): void
    {
        $response = $this->post('/register', [
            'name' => 'First User',
            'email' => 'first@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $user = \App\Models\User::where('email', 'first@example.com')->first();
        $this->assertEquals('admin', $user->role);
        $response->assertRedirect('/dashboard');
    }

    public function test_second_registered_user_becomes_member(): void
    {
        // First user
        \App\Models\User::create([
            'name' => 'First User',
            'email' => 'first@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $response = $this->post('/register', [
            'name' => 'Second User',
            'email' => 'second@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $user = \App\Models\User::where('email', 'second@example.com')->first();
        $this->assertEquals('member', $user->role);
        $response->assertRedirect('/dashboard');
    }

    public function test_banned_user_cannot_access_dashboard(): void
    {
        $user = \App\Models\User::factory()->create([
            'is_banned' => true,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertGuest();
        $response->assertSessionHas('error', 'Your account has been banned. Please contact the administrator.');
    }
}
