{{-- Search box for index tables; the slot holds an optional action button. --}}
<div class="row">
    @if ($slot->isNotEmpty())
        <div class="col-lg-2">
            {{ $slot }}
        </div>
    @endif

    <div class="{{ $slot->isNotEmpty() ? 'col-lg-10' : 'col-lg-12' }}">
        <form method="GET">
            <div class="input-group mb-2">
                <input type="search" class="form-control" name="s" value="{{ request('s') }}" placeholder="Nhập từ khóa tìm kiếm..." aria-label="Tìm kiếm">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>
