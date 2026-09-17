<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Contract tests for GET /api/product.
 *
 * Shipped client software parses these responses (including the historical
 * "lastest_version" key), so the body is asserted byte for byte.
 */
class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    private const PRODUCT_JSON = '{"id":1,"name":"App","description":"d","created_at":"2026-01-01T00:00:00.000000Z","updated_at":"2026-01-01T00:00:00.000000Z"}';

    public function test_product_with_versions_lists_newest_first(): void
    {
        $this->insertProduct();
        DB::table('versions')->insert([
            ['id' => 1, 'product_id' => 1, 'version' => '1.0.0', 'description' => 'x', 'file_url' => '/storage/a.zip', 'created_at' => '2026-01-01 00:00:00', 'updated_at' => null],
            ['id' => 2, 'product_id' => 1, 'version' => '1.1.0', 'description' => null, 'file_url' => null, 'created_at' => '2026-01-02 00:00:00', 'updated_at' => null],
        ]);

        $response = $this->getJson('/api/product?id=1');

        $v2 = '{"id":2,"product_id":1,"version":"1.1.0","description":null,"file_url":null,"created_at":"2026-01-02T00:00:00.000000Z","updated_at":null}';
        $v1 = '{"id":1,"product_id":1,"version":"1.0.0","description":"x","file_url":"\/storage\/a.zip","created_at":"2026-01-01T00:00:00.000000Z","updated_at":null}';

        $response->assertOk();
        $this->assertSame(
            '{"ok":true,"message":"SUCCESS","data":{"product":'.self::PRODUCT_JSON.',"lastest_version":'.$v2.',"versions":['.$v2.','.$v1.']}}',
            $response->getContent(),
        );
    }

    public function test_product_without_versions(): void
    {
        $this->insertProduct();

        $response = $this->getJson('/api/product?id=1');

        $response->assertOk();
        $this->assertSame(
            '{"ok":true,"message":"SUCCESS","data":{"product":'.self::PRODUCT_JSON.',"lastest_version":null,"versions":[]}}',
            $response->getContent(),
        );
    }

    public function test_unknown_product(): void
    {
        $response = $this->getJson('/api/product?id=999');

        $response->assertOk();
        $this->assertSame('{"ok":false,"message":"PRODUCT_NOT_FOUND"}', $response->getContent());
    }

    public function test_non_numeric_id_fails_validation_with_status_200(): void
    {
        $response = $this->getJson('/api/product?id=abc');

        $response->assertOk();
        $this->assertSame(
            '{"ok":false,"message":"VALIDATION_FAILED","errors":{"id":["The id must be a number."]}}',
            $response->getContent(),
        );
    }

    public function test_missing_id_fails_validation_with_status_200(): void
    {
        $response = $this->getJson('/api/product');

        $response->assertOk();
        $this->assertSame(
            '{"ok":false,"message":"VALIDATION_FAILED","errors":{"id":["The id field is required."]}}',
            $response->getContent(),
        );
    }

    private function insertProduct(): void
    {
        DB::table('products')->insert([
            'id' => 1,
            'name' => 'App',
            'description' => 'd',
            'created_at' => '2026-01-01 00:00:00',
            'updated_at' => '2026-01-01 00:00:00',
        ]);
    }
}
