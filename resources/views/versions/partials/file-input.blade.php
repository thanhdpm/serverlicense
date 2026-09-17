<div class="form-group mb-3">
    <label for="file">File cập nhật (nếu có)</label>
    <input type="file" id="file" class="form-control" name="file" accept="{{ collect(config('app.version_upload.extensions'))->map(fn ($extension) => '.'.$extension)->implode(',') }}">
    <small class="text-muted">
        Định dạng: {{ implode(', ', config('app.version_upload.extensions')) }}.
        Tối đa {{ number_format(config('app.version_upload.max_kb') / 1024) }} MB.
    </small>
    @isset($hint)
        <div class="text-danger">{{ $hint }}</div>
    @endisset
</div>
