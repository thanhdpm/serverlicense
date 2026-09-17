<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductLookupRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __invoke(ProductLookupRequest $request): JsonResponse
    {
        $product = Product::query()->find($request->validated('id'));

        if ($product === null) {
            return response()->json(['ok' => false, 'message' => 'PRODUCT_NOT_FOUND']);
        }

        $versions = $product->versions()->latest('id')->get();

        return response()->json([
            'ok' => true,
            'message' => 'SUCCESS',
            'data' => [
                'product' => $product,
                // The misspelled key is part of the public contract.
                'lastest_version' => $versions->first(),
                'versions' => $versions,
            ],
        ]);
    }
}
