<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Version;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('latestVersion')
            ->search($request->string('s')->toString())
            ->latest('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $file = $request->versionFile();
        $fileUrl = $file ? Version::storeUpload($file) : null;

        $product = DB::transaction(function () use ($request, $fileUrl) {
            $product = Product::query()->create($request->safe()->only(['name', 'description']));

            $product->versions()->create([
                'version' => $request->validated('version'),
                'description' => $request->validated('version_description'),
                'file_url' => $fileUrl,
            ]);

            return $product;
        });

        return to_route('products.index')->with('success', "Thêm sản phẩm \"{$product->name}\" thành công!");
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return to_route('products.edit', $product)->with('success', 'Đã lưu thay đổi thông tin sản phẩm!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {
            // Delete through Eloquent so each version also removes its file.
            $product->versions()->lazyById()->each(fn (Version $version) => $version->delete());
            $product->delete();
        });

        return to_route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}
