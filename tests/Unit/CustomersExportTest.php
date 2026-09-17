<?php

namespace Tests\Unit;

use App\Exports\CustomersExport;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomersExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_maps_customers_newest_first(): void
    {
        $this->travelTo('2026-01-02 03:04:05');
        $older = Customer::factory()->create(['fullname' => 'Older']);
        $newer = Customer::factory()->create([
            'fullname' => 'Alice Doe',
            'gender' => 'female',
            'dob' => '1990-12-31',
            'phone' => '0123456789',
            'email' => 'alice@example.com',
            'note' => 'VIP',
        ]);

        $export = new CustomersExport;

        $this->assertSame([$newer->id, $older->id], $export->query()->pluck('id')->all());
        $this->assertCount(count($export->headings()), $export->map($newer));
        $this->assertSame(
            [$newer->id, 'Alice Doe', 'Nữ', '31/12/1990', '0123456789', 'alice@example.com', 'VIP', '02/01/2026 03:04:05'],
            $export->map($newer->fresh()),
        );
    }
}
