<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Http\UploadedFile;

trait ValidatesVersionFile
{
    /**
     * @return list<string>
     */
    protected function versionFileRules(): array
    {
        return [
            'nullable',
            'file',
            'max:'.config('app.version_upload.max_kb'),
            'extensions:'.implode(',', (array) config('app.version_upload.extensions')),
        ];
    }

    public function versionFile(): ?UploadedFile
    {
        $file = $this->file('file');

        return $file instanceof UploadedFile ? $file : null;
    }
}
