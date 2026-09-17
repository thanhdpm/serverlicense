<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('auth.change-password');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        // The "hashed" cast on User hashes the plain password.
        $request->user()?->update(['password' => $request->validated('password')]);

        return to_route('password.edit')->with('success', 'Đổi mật khẩu thành công!');
    }
}
