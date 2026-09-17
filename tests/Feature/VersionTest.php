<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VersionTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake(Version::DISK);
        $this->signIn();
        $this->product = Product::factory()->create();
    }

    public function test_version_log_lists_versions_newest_first(): void
    {
        Version::factory()->for($this->product)->create(['version' => '1.0.0']);
        Version::factory()->for($this->product)->create(['version' => '1.1.0']);

        $this->get("/products/{$this->product->id}/versions")
            ->assertOk()
            ->assertSeeInOrder(['1.1.0', '1.0.0']);
    }

    public function test_version_can_be_added_with_a_file(): void
    {
        $this->post("/products/{$this->product->id}/versions", [
            'version' => '1.1.0',
            'description' => 'Feature release',
            'file' => UploadedFile::fake()->create('update.zip', 10),
        ])->assertRedirect("/products/{$this->product->id}/versions");

        $version = $this->product->versions()->sole();

        $this->assertSame('1.1.0', $version->version);
        $this->assertMatchesRegularExpression('#^/storage/[A-Za-z0-9]{40}\.zip$#', (string) $version->file_url);
        Storage::disk(Version::DISK)->assertExists((string) $version->storagePath());
    }

    public function test_script_uploads_are_rejected(): void
    {
        $this->post("/products/{$this->product->id}/versions", [
            'version' => '1.1.0',
            'file' => UploadedFile::fake()->createWithContent('shell.php', '<?php system($_GET["c"]);'),
        ])->assertSessionHasErrors('file');

        $this->assertDatabaseCount('versions', 0);
        $this->assertSame([], Storage::disk(Version::DISK)->allFiles());
    }

    public function test_edit_form_shows_the_current_version(): void
    {
        $version = Version::factory()->for($this->product)->create(['version' => '2.3.4']);

        $this->get("/versions/{$version->id}/edit")
            ->assertOk()
            ->assertSee('value="2.3.4"', false);
    }

    public function test_updating_without_a_file_keeps_the_existing_file(): void
    {
        $url = Version::storeUpload(UploadedFile::fake()->create('a.zip', 10));
        $version = Version::factory()->for($this->product)->create(['file_url' => $url]);

        $this->put("/versions/{$version->id}", ['version' => '9.9.9', 'description' => 'Updated'])
            ->assertRedirect("/versions/{$version->id}/edit");

        $this->assertSame('9.9.9', $version->fresh()->version);
        $this->assertSame($url, $version->fresh()->file_url);
        Storage::disk(Version::DISK)->assertExists((string) $version->storagePath());
    }

    public function test_replacing_the_file_deletes_the_old_one(): void
    {
        $version = Version::factory()->for($this->product)->create([
            'file_url' => Version::storeUpload(UploadedFile::fake()->create('old.zip', 10)),
        ]);
        $oldPath = (string) $version->storagePath();

        $this->put("/versions/{$version->id}", [
            'version' => $version->version,
            'file' => UploadedFile::fake()->create('new.zip', 10),
        ])->assertSessionHasNoErrors();

        Storage::disk(Version::DISK)->assertMissing($oldPath);
        Storage::disk(Version::DISK)->assertExists((string) $version->fresh()->storagePath());
    }

    public function test_deleting_a_version_deletes_its_file(): void
    {
        $version = Version::factory()->for($this->product)->create([
            'file_url' => Version::storeUpload(UploadedFile::fake()->create('build.zip', 10)),
        ]);

        $this->delete("/versions/{$version->id}")
            ->assertRedirect("/products/{$this->product->id}/versions");

        $this->assertModelMissing($version);
        Storage::disk(Version::DISK)->assertMissing((string) $version->storagePath());
    }
}
