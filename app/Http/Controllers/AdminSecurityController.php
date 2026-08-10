<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class AdminSecurityController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.security', [
            'user' => $request->user(),
        ]);
    }

    public function enable(
        Request $request,
        EnableTwoFactorAuthentication $enable
    ) {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        abort_if(
            $request->user()->two_factor_confirmed_at !== null,
            403
        );

        $enable($request->user());

        return back()->with(
            'status',
            'two-factor-authentication-enabled'
        );
    }

    public function confirm(
        Request $request,
        ConfirmTwoFactorAuthentication $confirm
    ) {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        abort_if(
            empty($request->user()->two_factor_secret),
            403
        );

        $confirm(
            $request->user(),
            $request->string('code')->toString()
        );

        return redirect()
            ->route('admin.security')
            ->with('status', 'two-factor-authentication-confirmed')
            ->with('show_recovery_codes', true);
    }
}
