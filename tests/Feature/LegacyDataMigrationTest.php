<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

/**
 * Runs the 2026_09_17 migrations against rows shaped like existing
 * production data. Each test gets its own in-memory database.
 */
class LegacyDataMigrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate')->assertSuccessful();
        $this->artisan('migrate:rollback', ['--step' => 3])->assertSuccessful();
    }

    public function test_customer_ids_are_backfilled_from_snapshots(): void
    {
        DB::table('customers')->insert([['id' => 1, 'fullname' => 'A'], ['id' => 2, 'fullname' => 'B']]);
        DB::table('licenses')->insert([
            ['id' => 1, 'key' => 'K1', 'customer' => '{"id":2,"fullname":"B"}'],
            ['id' => 2, 'key' => 'K2', 'customer' => '{"id":99,"fullname":"Deleted"}'],
            ['id' => 3, 'key' => 'K3', 'customer' => null],
            ['id' => 4, 'key' => 'K4', 'customer' => 'not json'],
        ]);

        $this->artisan('migrate')->assertSuccessful();

        $this->assertSame(
            [1 => 2, 2 => null, 3 => null, 4 => null],
            DB::table('licenses')->orderBy('id')->pluck('customer_id', 'id')->all(),
        );
    }

    public function test_duplicate_license_keys_stop_the_migration(): void
    {
        DB::table('licenses')->insert([['key' => 'SAME'], ['key' => 'SAME']]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Resolve these duplicate keys first: SAME');

        $this->artisan('migrate');
    }

    public function test_orphaned_versions_are_removed(): void
    {
        DB::table('products')->insert(['id' => 1, 'name' => 'App']);
        DB::table('versions')->insert([
            ['id' => 1, 'product_id' => 1, 'version' => '1.0.0'],
            ['id' => 2, 'product_id' => 999, 'version' => 'orphan'],
        ]);

        $this->artisan('migrate')->assertSuccessful();

        $this->assertSame([1], DB::table('versions')->pluck('id')->all());
    }
}
