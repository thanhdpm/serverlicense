<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\License;
use App\Models\Product;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_user_can_login_and_access_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);

        $dashboardResponse = $this->actingAs($this->user)->get('/');
        $dashboardResponse->assertStatus(200);
    }

    public function test_user_can_change_password(): void
    {
        $response = $this->actingAs($this->user)->post('/change-password', [
            'old' => 'password123',
            'new' => 'newpassword123',
            'confirm' => 'newpassword123',
        ]);

        $response->assertRedirect('/change-password');
        $this->user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $this->user->password));
    }

    public function test_customer_crud_operations(): void
    {
        // List customers
        $this->actingAs($this->user)->get('/customers')->assertStatus(200);

        // Add customer
        $addResponse = $this->actingAs($this->user)->post('/customers/add', [
            'fullname' => 'John Doe',
            'gender' => 'male',
            'dob' => '1995-05-20',
            'phone' => '0987654321',
            'email' => 'john@example.com',
            'note' => 'VIP Customer',
        ]);

        $addResponse->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', [
            'fullname' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $customer = Customer::where('email', 'john@example.com')->first();
        $this->assertNotNull($customer);

        // View profile & edit
        $this->actingAs($this->user)->get("/customer/{$customer->id}")->assertStatus(200);
        $this->actingAs($this->user)->get("/customer/{$customer->id}/edit")->assertStatus(200);

        // Save edit
        $saveResponse = $this->actingAs($this->user)->post("/customer/{$customer->id}/save", [
            'fullname' => 'John Updated',
            'gender' => 'male',
            'dob' => '1995-05-20',
            'phone' => '0987654321',
            'email' => 'john@example.com',
            'note' => 'VIP Customer Updated',
        ]);

        $saveResponse->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', [
            'fullname' => 'John Updated',
        ]);

        // Delete
        $deleteResponse = $this->actingAs($this->user)->get("/customer/{$customer->id}/delete");
        $deleteResponse->assertRedirect('/customers');
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_product_and_version_flow(): void
    {
        // Add product
        $response = $this->actingAs($this->user)->post('/products', [
            'name' => 'License Server Pro',
            'description' => 'A powerful license management tool',
            'version' => '1.0.0',
            'version_description' => 'Initial release',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['name' => 'License Server Pro']);
        $product = Product::where('name', 'License Server Pro')->first();
        $this->assertDatabaseHas('versions', ['product_id' => $product->id, 'version' => '1.0.0']);

        // Edit product
        $this->actingAs($this->user)->get("/product/{$product->id}")->assertStatus(200);
        $this->actingAs($this->user)->post("/product/{$product->id}/save", [
            'name' => 'License Server Pro Edition',
            'description' => 'Updated description',
        ])->assertRedirect();

        // Version log
        $this->actingAs($this->user)->get("/product/version-log/{$product->id}")->assertStatus(200);

        // Add version
        $this->actingAs($this->user)->post("/product/version-log/{$product->id}", [
            'version' => '1.1.0',
            'description' => 'Feature release',
        ])->assertRedirect();

        $this->assertDatabaseHas('versions', ['product_id' => $product->id, 'version' => '1.1.0']);

        // API product
        $apiResponse = $this->getJson("/api/product?id={$product->id}");
        $apiResponse->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'message' => 'SUCCESS',
            ]);
    }

    public function test_license_creation_and_api_activation(): void
    {
        $customer = Customer::create([
            'fullname' => 'Jane Smith',
            'gender' => 'female',
            'email' => 'jane@example.com',
        ]);

        $product = Product::create([
            'name' => 'Test App',
            'description' => 'App description',
        ]);

        // Add license
        $licenseKey = 'TEST-KEY-1234-5678';
        $response = $this->actingAs($this->user)->post('/licenses/add', [
            'product' => $product->id,
            'customer' => $customer->id,
            'key' => $licenseKey,
            'duration_value' => 30,
            'duration_period' => 'days',
        ]);

        $response->assertRedirect('/licenses');
        $this->assertDatabaseHas('licenses', ['key' => $licenseKey]);

        // API License validation & activation
        $apiResponse = $this->getJson("/api/license?key={$licenseKey}");
        $apiResponse->assertStatus(200)
            ->assertJson([
                'ok' => true,
                'message' => 'VALID_LICENSE',
            ]);

        $license = License::where('key', $licenseKey)->first();
        $this->assertNotNull($license->activated_at);
        $this->assertNotNull($license->fingerprint);

        // API License with invalid key
        $invalidResponse = $this->getJson('/api/license?key=INVALID-KEY');
        $invalidResponse->assertStatus(200)
            ->assertJson([
                'ok' => false,
                'message' => 'INVALID_LICENSE',
            ]);
    }
}
