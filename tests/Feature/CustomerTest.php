<?php

namespace Tests\Feature;

use App\Exports\CustomersExport;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_customer_can_be_created_with_a_day_first_birth_date(): void
    {
        $this->get('/customers/create')->assertOk();

        $this->post('/customers', $this->validPayload())
            ->assertRedirect('/customers')
            ->assertSessionHas('success', 'Thêm khách hàng "John Doe" thành công!');

        $customer = Customer::query()->sole();
        $this->assertSame('1995-05-20', $customer->dob?->toDateString());
        $this->assertSame('male', $customer->gender);
    }

    public function test_edit_form_round_trips_the_birth_date(): void
    {
        $customer = Customer::factory()->create(['dob' => '1995-05-20']);

        $this->get("/customers/{$customer->id}/edit")
            ->assertOk()
            ->assertSee('value="20/05/1995"', false);

        $this->put("/customers/{$customer->id}", $this->validPayload(['fullname' => 'John Updated']))
            ->assertRedirect('/customers')
            ->assertSessionHasNoErrors();

        $this->assertSame('John Updated', $customer->fresh()->fullname);
        $this->assertSame('1995-05-20', $customer->fresh()->dob?->toDateString());
    }

    public function test_invalid_input_is_rejected(): void
    {
        $this->from('/customers/create')
            ->post('/customers', $this->validPayload(['gender' => 'robot', 'dob' => '1995-05-20', 'email' => 'nope']))
            ->assertRedirect('/customers/create')
            ->assertSessionHasErrors(['gender', 'dob', 'email']);

        $this->assertDatabaseCount('customers', 0);
    }

    public function test_profile_page_and_missing_profile(): void
    {
        $customer = Customer::factory()->create(['fullname' => 'Jane Smith']);

        $this->get("/customers/{$customer->id}")->assertOk()->assertSee('Jane Smith');

        $this->get('/customers/999')
            ->assertRedirect('/customers')
            ->assertSessionHasErrors();
    }

    public function test_customer_can_be_deleted(): void
    {
        $customer = Customer::factory()->create();

        $this->delete("/customers/{$customer->id}")->assertRedirect('/customers');

        $this->assertModelMissing($customer);
    }

    public function test_index_escapes_contact_details(): void
    {
        Customer::factory()->create(['email' => '"><script>alert(1)</script>']);

        $this->get('/customers')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_search_is_kept_in_pagination_links(): void
    {
        Customer::factory()->count(12)->create(['fullname' => 'Nguyen Van A']);
        Customer::factory()->create(['fullname' => 'Someone Else']);

        $response = $this->get('/customers?s=Nguyen&row=10');

        $response->assertOk()
            ->assertDontSee('Someone Else')
            ->assertSee('s=Nguyen&amp;row=10&amp;page=2', false);
        $this->assertSame(12, $response->viewData('customers')->total());
    }

    public function test_page_size_is_capped(): void
    {
        $this->get('/customers?row=100000')
            ->assertOk()
            ->assertViewHas('customers', fn ($customers) => $customers->perPage() === 100);
    }

    public function test_sidebar_customer_count_is_refreshed_when_customers_change(): void
    {
        Customer::factory()->count(2)->create();
        $this->assertSidebarCustomerCount(2);

        $customer = Customer::factory()->create();
        $this->assertSidebarCustomerCount(3);

        $customer->delete();
        $this->assertSidebarCustomerCount(2);
    }

    public function test_customers_can_be_exported(): void
    {
        Excel::fake();
        $this->travelTo('2026-01-02 03:04:05');

        $this->get('/customers/export')->assertOk();

        Excel::assertDownloaded('customers-20260102-030405.xlsx', fn ($export) => $export instanceof CustomersExport);
    }

    private function assertSidebarCustomerCount(int $expected): void
    {
        $this->assertMatchesRegularExpression(
            '#<span class="badge rounded-pill bg-danger float-end">\s*'.$expected.'\s*</span>#',
            (string) $this->get('/customers')->getContent(),
        );
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'fullname' => 'John Doe',
            'gender' => 'male',
            'dob' => '20/05/1995',
            'phone' => '0987654321',
            'email' => 'john@example.com',
            'note' => 'VIP Customer',
        ], $overrides);
    }
}
