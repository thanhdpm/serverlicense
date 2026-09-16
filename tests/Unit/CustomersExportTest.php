<?php

namespace Tests\Unit;

use App\Exports\CustomersExport;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomersExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_export_returns_collection(): void
    {
        Customer::create([
            'fullname' => 'Alice Doe',
            'gender' => 'female',
            'email' => 'alice@example.com',
        ]);

        $export = new CustomersExport();
        $collection = $export->collection();

        $this->assertCount(1, $collection);
        $this->assertEquals('Alice Doe', $collection->first()->fullname);
    }
}
