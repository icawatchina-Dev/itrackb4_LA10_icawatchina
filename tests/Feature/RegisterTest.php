<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    public function test_register_page_has_a_form_with_password_confirmation(): void
    {
        $this->get(route('register.create'))
            ->assertOk()
            ->assertSee('action="'.route('register.store').'"', false)
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('name="_token"', false);
    }

    public function test_mismatched_passwords_are_rejected(): void
    {
        $this->from(route('register.create'))
            ->post(route('register.store'), [
                'name' => 'Test User',
                'email' => 'mismatch@example.com',
                'password' => 'password-one',
                'password_confirmation' => 'password-two',
            ])
            ->assertRedirect(route('register.create'))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'mismatch@example.com']);
    }

    public function test_duplicate_email_is_rejected(): void
    {
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => 'existing-password',
        ]);

        $this->from(route('register.create'))
            ->post(route('register.store'), [
                'name' => 'Another User',
                'email' => 'existing@example.com',
                'password' => 'valid-password',
                'password_confirmation' => 'valid-password',
            ])
            ->assertRedirect(route('register.create'))
            ->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', 'existing@example.com')->count());
    }

    public function test_valid_registration_creates_and_authenticates_the_user(): void
    {
        $this->post(route('register.store'), [
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'password' => 'valid-password',
            'password_confirmation' => 'valid-password',
        ])->assertRedirect(route('home'))
            ->assertSessionHas('success', 'Welcome, New User!');

        $user = User::where('email', 'new-user@example.com')->firstOrFail();
        $this->assertSame('New User', $user->name);
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('valid-password', $user->password));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Welcome, New User!')
            ->assertSee('Signed in as New User');
    }
}
