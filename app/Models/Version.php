<?php

namespace App\Models;

use App\Models\Concerns\HasKeywordSearch;
use Database\Factories\VersionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

#[Fillable(['version', 'description', 'file_url'])]
class Version extends Model
{
    /** @use HasFactory<VersionFactory> */
    use HasFactory, HasKeywordSearch;

    public const DISK = 'public';

    /**
     * The API exposes uploads as root-relative "/storage/..." URLs.
     */
    private const URL_PREFIX = '/storage/';

    protected static function booted(): void
    {
        static::updated(function (Version $version) {
            if ($version->wasChanged('file_url')) {
                self::deleteFileAfterCommit(self::pathFromUrl($version->getOriginal('file_url')));
            }
        });

        static::deleted(function (Version $version) {
            self::deleteFileAfterCommit($version->storagePath());
        });
    }

    /**
     * Store an upload under a random name and return its public URL. The
     * extension comes from the validated client filename, never from MIME
     * sniffing, so a disguised script can't end up with a ".php" name.
     */
    public static function storeUpload(UploadedFile $file): string
    {
        $name = Str::random(40).'.'.Str::lower($file->getClientOriginalExtension());

        $path = Storage::disk(self::DISK)->putFileAs('', $file, $name)
            ?: throw new RuntimeException('Could not store the uploaded version file.');

        return self::URL_PREFIX.$path;
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Location of the uploaded file on the public disk, if there is one.
     */
    public function storagePath(): ?string
    {
        return self::pathFromUrl($this->file_url);
    }

    protected function searchableColumns(): array
    {
        return ['version', 'description', 'id'];
    }

    private static function pathFromUrl(mixed $url): ?string
    {
        if (! is_string($url) || ! str_starts_with($url, self::URL_PREFIX)) {
            return null;
        }

        return substr($url, strlen(self::URL_PREFIX));
    }

    private static function deleteFileAfterCommit(?string $path): void
    {
        if ($path !== null) {
            DB::afterCommit(fn () => Storage::disk(self::DISK)->delete($path));
        }
    }
}
