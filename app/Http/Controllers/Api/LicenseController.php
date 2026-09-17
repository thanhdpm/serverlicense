<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LicenseLookupRequest;
use App\Models\License;
use Illuminate\Http\JsonResponse;

class LicenseController extends Controller
{
    public function __invoke(LicenseLookupRequest $request): JsonResponse
    {
        $license = License::query()->firstWhere('key', $request->validated('key'));

        if ($license === null) {
            return response()->json(['ok' => false, 'message' => 'INVALID_LICENSE']);
        }

        if ($license->isExpired()) {
            return response()->json(['ok' => false, 'message' => 'EXPIRED_LICENSE']);
        }

        if ($license->activated_at === null) {
            // Clients have always received the license as it was before this
            // first activation, so the in-memory model is left untouched.
            $license->activate($request->ip(), $request->userAgent());
        }

        return response()->json(['ok' => true, 'message' => 'VALID_LICENSE', 'data' => $license]);
    }
}
