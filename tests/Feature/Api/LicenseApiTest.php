<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Contract tests for GET /api/license.
 *
 * Shipped client software parses these responses, so the body is asserted
 * byte for byte (key order, value types and escaping included).
 */
class LicenseApiTest extends TestCase
{
    use RefreshDatabase;

    private const CUSTOMER_SNAPSHOT = '{"id":1,"fullname":"Jane","gender":"female","dob":"1995-05-20","phone":null,"email":"j@e.com","note":null,"created_at":"2026-01-01T00:00:00.000000Z","updated_at":"2026-01-01T00:00:00.000000Z"}';

    private const PRODUCT_SNAPSHOT = '{"id":1,"name":"App","description":"d","created_at":"2026-01-01T00:00:00.000000Z","updated_at":"2026-01-01T00:00:00.000000Z"}';

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo('2026-01-01 00:00:00');
    }

    public function test_first_activation_returns_the_pre_activation_snapshot(): void
    {
        $this->insertLicense();

        $response = $this->withHeader('User-Agent', 'UA/1.0')->getJson('/api/license?key=K-1');

        $response->assertOk();
        $this->assertSame(
            '{"ok":true,"message":"VALID_LICENSE","data":{"id":1,"customer":'.self::CUSTOMER_SNAPSHOT.',"product":'.self::PRODUCT_SNAPSHOT.',"product_id":1,"key":"K-1","duration":"3155760000","fingerprint":null,"activated_at":null,"created_at":"2026-01-01 00:00:00"}}',
            $response->getContent(),
        );

        $row = DB::table('licenses')->where('key', 'K-1')->first();
        $this->assertNotNull($row->activated_at);
        $this->assertSame(['ip' => '127.0.0.1', 'useragent' => 'UA/1.0'], json_decode($row->fingerprint, true));
    }

    public function test_activated_license_returns_activation_data_and_keeps_first_fingerprint(): void
    {
        $this->insertLicense();

        $this->withHeader('User-Agent', 'UA/1.0')->getJson('/api/license?key=K-1')->assertOk();
        $response = $this->withHeader('User-Agent', 'Other/2.0')->getJson('/api/license?key=K-1');

        $response->assertOk();
        $this->assertSame(
            '{"ok":true,"message":"VALID_LICENSE","data":{"id":1,"customer":'.self::CUSTOMER_SNAPSHOT.',"product":'.self::PRODUCT_SNAPSHOT.',"product_id":1,"key":"K-1","duration":"3155760000","fingerprint":{"ip":"127.0.0.1","useragent":"UA\/1.0"},"activated_at":"2026-01-01T00:00:00.000000Z","created_at":"2026-01-01 00:00:00"}}',
            $response->getContent(),
        );
    }

    public function test_expired_license(): void
    {
        $this->insertLicense(['activated_at' => '2025-01-01 00:00:00', 'duration' => '60']);

        $response = $this->getJson('/api/license?key=K-1');

        $response->assertOk();
        $this->assertSame('{"ok":false,"message":"EXPIRED_LICENSE"}', $response->getContent());
    }

    public function test_unknown_key_is_invalid(): void
    {
        $this->insertLicense();

        $response = $this->getJson('/api/license?key=NOPE');

        $response->assertOk();
        $this->assertSame('{"ok":false,"message":"INVALID_LICENSE"}', $response->getContent());
    }

    public function test_missing_key_fails_validation_with_status_200(): void
    {
        $response = $this->getJson('/api/license');

        $response->assertOk();
        $this->assertSame(
            '{"ok":false,"message":"VALIDATION_FAILED","errors":{"key":["The key field is required."]}}',
            $response->getContent(),
        );
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function insertLicense(array $overrides = []): void
    {
        DB::table('licenses')->insert(array_merge([
            'id' => 1,
            'customer' => self::CUSTOMER_SNAPSHOT,
            'product' => self::PRODUCT_SNAPSHOT,
            'product_id' => 1,
            'key' => 'K-1',
            'duration' => '3155760000',
            'fingerprint' => null,
            'activated_at' => null,
            'created_at' => '2026-01-01 00:00:00',
        ], $overrides));
    }
}
