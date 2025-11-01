<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberAuthController extends Controller
{
    public function showLogin()
    {
        return view('member.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'nullable|email',
            'civil_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt login with civil_id and password (preferred method)
        if (Auth::guard('members')->attempt(['civil_id' => $credentials['civil_id'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $member = Auth::guard('members')->user();
            
            // Check if member is approved (either login_status OR renewal_status)
            if ($member->login_status !== 'approved' && $member->renewal_status !== 'approved') {
                Auth::guard('members')->logout();
                return back()->withErrors([
                    'civil_id' => 'Your membership is under review. Please wait for admin approval.',
                ])->withInput();
            }

            // Check if card is expired (but still allow login to see dashboard)
            // The dashboard will show renewal request option
            
            return redirect()->route('member.dashboard');
        }

        // If civil_id auth fails and email is provided, try email
        if (!empty($credentials['email'])) {
            if (Auth::guard('members')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
                $request->session()->regenerate();

                $member = Auth::guard('members')->user();
                
                // Check civil_id matches
                if (trim((string)$member->civil_id) !== trim((string)$credentials['civil_id'])) {
                    Auth::guard('members')->logout();
                    return back()->withErrors([
                        'civil_id' => 'Civil ID does not match our records.',
                    ])->withInput();
                }
                
                // Check if member is approved (either login_status OR renewal_status)
                if ($member->login_status !== 'approved' && $member->renewal_status !== 'approved') {
                    Auth::guard('members')->logout();
                    return back()->withErrors([
                        'civil_id' => 'Your membership is under review. Please wait for admin approval.',
                    ])->withInput();
                }

                return redirect()->route('member.dashboard');
            }
        }

        return back()->withErrors([
            'civil_id' => 'Invalid credentials. Please check your Civil ID and password.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('members')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('member.login');
    }
}


