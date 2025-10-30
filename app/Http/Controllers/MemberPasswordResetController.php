<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class MemberPasswordResetController extends Controller
{
    /**
     * Show the password reset request form
     */
    public function showLinkRequestForm()
    {
        return view('member.auth.password-request');
    }

    /**
     * Send password reset link to member's email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Send password reset link using members broker
        $status = Password::broker('members')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the password reset form
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('member.auth.password-reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset the member's password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('members')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($member, $password) {
                $member->forceFill([
                    'password' => $password,
                ])->save();

                event(new PasswordReset($member));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/member/panel/login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}

