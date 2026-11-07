<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Welcome Back');
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_users_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'kyrie@example.com',
            'password' => 'password123',
        ]);

        $this->post(route('login.attempt'), [
            'email' => 'kyrie@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_users_cannot_log_in_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'kyrie@example.com',
            'password' => 'password123',
        ]);

        $this->post(route('login.attempt'), [
            'email' => 'kyrie@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_the_seeded_demo_account_can_log_in(): void
    {
        $this->seed();

        $this->post(route('login.attempt'), [
            'email' => 'demo@pennywise.test',
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_the_seeded_dummy_accounts_can_log_in_with_usernames(): void
    {
        $this->seed();

        foreach ([['dummy1', 'password1'], ['dummy2', 'password2']] as [$username, $password]) {
            $this->post(route('login.attempt'), [
                'email' => $username,
                'password' => $password,
            ])->assertRedirect(route('dashboard'));

            $this->assertAuthenticated();

            $this->post(route('logout'))->assertRedirect(route('login'));
        }

        $this->assertGuest();
    }

    public function test_users_can_register(): void
    {
        $this->post(route('register.attempt'), [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    public function test_users_can_log_out(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
