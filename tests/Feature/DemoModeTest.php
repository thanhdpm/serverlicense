<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoModeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.demo' => true]);
        $this->signIn();
    }

    public function test_pages_can_still_be_viewed(): void
    {
        $this->get('/customers')->assertOk();
        $this->get('/licenses/create')->assertOk();
    }

    public function test_writes_are_blocked(): void
    {
        $customer = Customer::factory()->create();

        $this->post('/customers', ['fullname' => 'X', 'gender' => 'male'])->assertForbidden();
        $this->put("/customers/{$customer->id}", ['fullname' => 'X', 'gender' => 'male'])->assertForbidden();
        $this->delete("/customers/{$customer->id}")->assertForbidden();
        $this->post('/licenses', [])->assertForbidden();
        $this->put('/change-password', [])->assertForbidden();

        $this->assertModelExists($customer);
    }

    public function test_users_can_still_log_out(): void
    {
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
}
