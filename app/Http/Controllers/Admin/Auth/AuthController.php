<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('pages.auth.login');
    }

    public function loginPost(Request $request)
    {
        try{
            $validate = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (auth()->attempt($validate)) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->back()->with('error', 'Email or password is wrong');
            }
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Email or password is wrong');
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }
}
