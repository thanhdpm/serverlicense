<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_user_can_login_and_see_the_dashboard(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.com', 'password' => 'password123']);

        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'password123'])
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
        $this->get('/')->assertOk()->assertSee('Đăng nhập thành công!');
    }

    public function test_wrong_password_is_rejected(): void
    {
        User::factory()->create(['email' => 'admin@example.com']);

        $this->from('/login')
            ->post('/login', ['email' => 'admin@example.com', 'password' => 'wrong-password'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_locked_after_too_many_failed_attempts(): void
    {
        $this->freezeTime();
        User::factory()->create(['email' => 'admin@example.com', 'password' => 'password123']);

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', ['email' => 'admin@example.com', 'password' => 'wrong-password']);
        }

        $this->post('/login', ['email' => 'admin@example.com', 'password' => 'password123'])
            ->assertSessionHasErrors(['email' => 'Đăng nhập sai quá nhiều lần. Vui lòng thử lại sau 60 giây.']);

        $this->assertGuest();
    }

    public function test_logout_requires_post_and_ends_the_session(): void
    {
        $this->signIn();

        $this->get('/logout')->assertMethodNotAllowed();

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_user_can_change_password(): void
    {
        $user = $this->signIn(User::factory()->create(['password' => 'password123']));

        $this->put('/change-password', [
            'current_password' => 'password123',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertRedirect('/change-password')->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_password_change_requires_the_current_password(): void
    {
        $user = $this->signIn(User::factory()->create(['password' => 'password123']));

        $this->from('/change-password')->put('/change-password', [
            'current_password' => 'not-my-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertSessionHasErrors(['current_password' => 'Mật khẩu cũ không hợp lệ!']);

        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }
}
