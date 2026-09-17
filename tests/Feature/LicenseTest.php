<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\License;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LicenseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_create_form_lists_products_and_customers(): void
    {
        Product::factory()->create(['name' => 'Test App']);
        Customer::factory()->create(['fullname' => 'Jane Smith']);

        $this->get('/licenses/create')
            ->assertOk()
            ->assertSee('Test App')
            ->assertSee('Jane Smith');
    }

    public function test_license_is_created_with_customer_and_product_snapshots(): void
    {
        $customer = Customer::factory()->create(['dob' => '1995-05-20']);
        $product = Product::factory()->create();

        $this->post('/licenses', [
            'product' => $product->id,
            'customer' => $customer->id,
            'key' => 'TEST-KEY-1234',
            'duration_value' => 30,
            'duration_period' => 'days',
        ])->assertRedirect('/licenses');

        $license = License::query()->sole();

        $this->assertSame('TEST-KEY-1234', $license->key);
        $this->assertSame(2_592_000, $license->durationInSeconds());
        $this->assertSame($customer->id, $license->customer_id);
        $this->assertSame($customer->fresh()->toArray(), $license->customer);
        $this->assertSame('1995-05-20', $license->customer['dob']);
        $this->assertSame($product->fresh()->toArray(), $license->product);
        $this->assertNull($license->activated_at);
    }

    public function test_duplicate_keys_are_rejected_even_after_activation(): void
    {
        $existing = License::factory()->activated()->create(['key' => 'DUPLICATE']);

        $this->post('/licenses', [
            'product' => $existing->product_id,
            'customer' => $existing->customer_id,
            'key' => 'DUPLICATE',
            'duration_value' => 1,
            'duration_period' => 'years',
        ])->assertSessionHasErrors(['key' => 'Mã kích hoạt đã tồn tại.']);

        $this->assertDatabaseCount('licenses', 1);
    }

    public function test_unknown_product_or_customer_is_a_validation_error(): void
    {
        $this->post('/licenses', [
            'product' => 999,
            'customer' => 999,
            'key' => 'KEY',
            'duration_value' => 1,
            'duration_period' => 'fortnights',
        ])->assertSessionHasErrors(['product', 'customer', 'duration_period']);

        $this->assertDatabaseCount('licenses', 0);
    }

    public function test_index_renders_activated_licenses(): void
    {
        License::factory()->activated('10.0.0.1', 'Client/1.0')->create(['key' => 'SHOWN-KEY']);
        License::factory()->create();

        $this->get('/licenses')
            ->assertOk()
            ->assertSee('SHOWN-KEY')
            ->assertSee('data-ip="10.0.0.1"', false)
            ->assertSee('4 tuần');
    }

    public function test_index_escapes_untrusted_fingerprints_and_snapshots(): void
    {
        $customer = Customer::factory()->create(['fullname' => '<img src=x onerror=alert(1)>']);
        License::factory()
            ->activated('1.1.1.1', "'});alert(1);//<script>alert(2)</script>")
            ->create(['customer_id' => $customer->id]);

        $response = $this->get('/licenses')->assertOk();

        $response->assertDontSee('<script>alert(2)</script>', false);
        $response->assertDontSee('<img src=x', false);
        // Popover HTML is escaped twice: once for the popover, once for the attribute.
        $response->assertSee('&amp;lt;img src=x onerror=alert(1)&amp;gt;', false);
    }

    public function test_license_duration_can_be_updated(): void
    {
        $license = License::factory()->create();

        $this->get("/licenses/{$license->id}/edit")->assertOk();

        $this->put("/licenses/{$license->id}", ['duration_value' => 2, 'duration_period' => 'weeks'])
            ->assertRedirect("/licenses/{$license->id}/edit");

        $this->assertSame(1_209_600, $license->fresh()->durationInSeconds());
    }

    public function test_license_can_be_deleted(): void
    {
        $license = License::factory()->create();

        $this->delete("/licenses/{$license->id}")->assertRedirect('/licenses');

        $this->assertModelMissing($license);
    }
}
