<?php

namespace App\Http\Controllers;

use App\Http\Requests\VersionRequest;
use App\Models\Product;
use App\Models\Version;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VersionController extends Controller
{
    private const PER_PAGE = 5;

    public function index(Request $request, Product $product): View
    {
        $versions = $product->versions()
            ->search($request->string('s')->toString())
            ->latest('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('versions.index', compact('product', 'versions'));
    }

    public function store(VersionRequest $request, Product $product): RedirectResponse
    {
        $file = $request->versionFile();

        $version = $product->versions()->create([
            ...$request->safe()->only(['version', 'description']),
            'file_url' => $file ? Version::storeUpload($file) : null,
        ]);

        return to_route('products.versions.index', $product)
            ->with('success', "Thêm phiên bản mới \"{$version->version}\" thành công!");
    }

    public function edit(Version $version): View
    {
        return view('versions.edit', compact('version'));
    }

    public function update(VersionRequest $request, Version $version): RedirectResponse
    {
        $attributes = $request->safe()->only(['version', 'description']);

        // Keep the current file unless a replacement was uploaded; the model
        // deletes the replaced file once the update is committed.
        if ($file = $request->versionFile()) {
            $attributes['file_url'] = Version::storeUpload($file);
        }

        $version->update($attributes);

        return to_route('versions.edit', $version)->with('success', 'Đã lưu thay đổi thông tin phiên bản!');
    }

    public function destroy(Version $version): RedirectResponse
    {
        $version->delete();

        return to_route('products.versions.index', $version->product_id)
            ->with('success', 'Xóa phiên bản thành công!');
    }
}
