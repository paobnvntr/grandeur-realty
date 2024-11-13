<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            '_token' => 'required',
            'username' => 'required',
            'password' => 'required'
        ])->validate();

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::create([
                'type' => 'Login',
                'user' => $request->username,
                'subject' => 'Login Failed',
                'message' => $request->username . ' has unsuccessfully logged in.',
                'created_at' => now('Asia/Manila'),
                'updated_at' => now('Asia/Manila'),
            ]);

            throw ValidationException::withMessages([
                'username' => trans('auth.failed')
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        Log::create([
            'type' => 'Login',
            'user' => $request->username,
            'subject' => 'Login Success',
            'message' => $request->username . ' has successfully logged in.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $username = Auth::user()->username;
        Log::create([
            'type' => 'Logout',
            'user' => $username,
            'subject' => 'Logout Success',
            'message' => $username . ' has successfully logged out.',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/login')->with('success', 'Logged Out Successfully!');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'username' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('username', $value)->first();
                    if (!$user) {
                        $fail('No account found with the ' . $attribute . ' provided.');
                    }
                },
            ],
        ]);

        if (DB::table('password_reset_tokens')->where('email', User::where('username', $request->username)->value('email'))->exists()) {
            return redirect()->route('login')->with('error', 'A reset password link has already been sent to your email.');
        } else {
            $token = Str::random(64);

            $email = User::where('username', $request->username)->value('email');

            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now('Asia/Manila')
            ]);

            Mail::send('email.forgetPasswordMail', ['token' => $token, 'username' => $request->username], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Grandeur Realty - Reset Password');
            });

            return redirect()->route('login')->with('success', 'Mail Sent Successfully!');
        }
    }

    public function changePasswordForm($token, $username)
    {
        return view('auth.changePassword', ['token' => $token, 'username' => $username]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'username' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('username', $value)->first();
                    if (!$user) {
                        $fail('No account found with the ' . $attribute . ' provided.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) use ($request) {
                    $user = User::where('username', $request->input('username'))->first();
                    if ($user && Hash::check($value, $user->password)) {
                        $fail('The new password cannot be the same as the current password.');
                    }
                },
            ],
        ]);

        $email = User::where('username', $request->username)->value('email');

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        User::where('username', $request->username)->update(['password' => Hash::make($request->password)]);

        return redirect()->route('login')->with('success', 'Your password has been changed!');
    }
}
