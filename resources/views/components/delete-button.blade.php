@props(['action', 'title' => 'Xóa', 'confirm' => 'Bạn có chắc chắn muốn xóa?'])

<form method="POST" action="{{ $action }}" class="d-inline" data-confirm="{{ $confirm }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $title }}">
        <i class="fas fa-trash"></i>
    </button>
</form>
