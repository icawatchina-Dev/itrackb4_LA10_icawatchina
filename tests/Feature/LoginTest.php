<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_page_has_a_csrf_protected_form(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('action="'.route('login.store').'"', false)
            ->assertSee('name="_token"', false);
    }

    public function test_invalid_credentials_show_one_generic_error_without_old_password(): void
    {
        $user = User::create([
            'name' => 'Login Test User',
            'email' => 'login-test@example.com',
            'password' => 'correct-password',
        ]);

        $response = $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->assertSessionHas('_old_input.email', $user->email);

        $this->assertArrayNotHasKey('password', session('_old_input', []));

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'unknown-login@example.com',
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
    }

    public function test_valid_credentials_authenticate_the_user(): void
    {
        $user = User::create([
            'name' => 'Login Test User',
            'email' => 'successful-login@example.com',
            'password' => 'correct-password',
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])
            ->assertRedirect(route('brgys.index'));

        $this->assertSame('Login Successfully', session('success'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_returns_guest_to_the_intended_create_page(): void
    {
        $user = User::create([
            'name' => 'Intended Login User',
            'email' => 'intended-login@example.com',
            'password' => 'correct-password',
        ]);

        $this->get(route('brgys.create'))
            ->assertRedirect(route('login'));

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])
            ->assertRedirect(route('brgys.create'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_logs_out_and_rejects_get_requests(): void
    {
        $user = User::create([
            'name' => 'Logout Test User',
            'email' => 'logout-test@example.com',
            'password' => 'correct-password',
        ]);

        $this->actingAs($user)
            ->get('/logout')
            ->assertMethodNotAllowed();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'))
            ->assertSessionHas('success', 'You have been logged out.');

        $this->assertGuest();
    }
}
