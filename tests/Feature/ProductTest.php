<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(Version::DISK);
        $this->signIn();
    }

    public function test_product_is_created_with_its_first_version(): void
    {
        $this->post('/products', [
            'name' => 'License Server Pro',
            'description' => 'A license management tool',
            'version' => '1.0.0',
            'version_description' => 'Initial release',
            'file' => UploadedFile::fake()->create('release.zip', 10),
        ])->assertRedirect('/products');

        $product = Product::query()->sole();
        $version = $product->versions()->sole();

        $this->assertSame('License Server Pro', $product->name);
        $this->assertSame('1.0.0', $version->version);
        $this->assertSame('Initial release', $version->description);
        $this->assertStringEndsWith('.zip', (string) $version->file_url);
        Storage::disk(Version::DISK)->assertExists((string) $version->storagePath());
    }

    public function test_product_can_be_updated(): void
    {
        $product = Product::factory()->create();

        $this->get("/products/{$product->id}/edit")->assertOk();

        $this->put("/products/{$product->id}", ['name' => 'Renamed', 'description' => null])
            ->assertRedirect("/products/{$product->id}/edit");

        $this->assertSame('Renamed', $product->fresh()->name);
        $this->assertNull($product->fresh()->description);
    }

    public function test_deleting_a_product_removes_its_versions_and_files(): void
    {
        $product = Product::factory()->create();
        $file = Version::storeUpload(UploadedFile::fake()->create('build.zip', 10));
        $version = Version::factory()->for($product)->create(['file_url' => $file]);

        $this->delete("/products/{$product->id}")->assertRedirect('/products');

        $this->assertModelMissing($product);
        $this->assertModelMissing($version);
        Storage::disk(Version::DISK)->assertMissing((string) $version->storagePath());
    }

    public function test_index_shows_latest_versions_without_n_plus_one_queries(): void
    {
        $this->get('/products')->assertOk(); // warm the sidebar count cache

        $queriesFor = function (int $products): int {
            Product::factory()->count($products)->has(Version::factory()->count(2))->create();

            DB::flushQueryLog();
            DB::enableQueryLog();
            $this->get('/products?row=100')->assertOk();
            DB::disableQueryLog();

            return count(DB::getQueryLog());
        };

        $this->assertSame($queriesFor(2), $queriesFor(10));
    }

    public function test_index_displays_the_newest_version(): void
    {
        $product = Product::factory()->create();
        Version::factory()->for($product)->create(['version' => 'build-older']);
        Version::factory()->for($product)->create(['version' => 'build-newest']);

        $this->get('/products')
            ->assertOk()
            ->assertSee('build-newest')
            ->assertDontSee('build-older');
    }
}
