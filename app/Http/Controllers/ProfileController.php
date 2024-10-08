<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function validateProfileForm(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function updateProfile(Request $request, string $id)
    {
        $user = User::find($id);

        if ($this->shouldUpdateProfileDetails($user, $request)) {
            $this->updateProfileDetails($user, $request);

            $this->logProfileUpdate($user);

            return redirect()
                ->route('profile')
                ->with('success', 'Profile updated successfully!');
        } else {
            return redirect()
                ->route('profile')
                ->with('failed', 'Please edit a field!');
        }
    }

    private function shouldUpdateProfileDetails(User $user, Request $request)
    {
        return (
            $request->input('name') !== $user->name ||
            $request->input('username') !== $user->username ||
            $request->input('email') !== $user->email ||
            $request->filled('password')
        );
    }

    private function updateProfileDetails(User $user, Request $request)
    {
        $user->name = $request->filled('name') ? trim($request->name) : $user->name;
        $user->username = $request->filled('username') ? trim($request->username) : $user->username;
        $user->email = $request->filled('email') ? trim($request->email) : $user->email;

        if ($request->filled('password')) {
            $user->password = Hash::make(trim($request->password));
        }

        $user->updated_at = now('Asia/Manila');
        $user->update();
    }

    private function logProfileUpdate(User $user)
    {
        $logType = 'Edit Profile';
        $logUser = Auth::user()->username;

        if ($user->wasChanged()) {
            $logSubject = 'Profile Update Success';
            $logMessage = "$logUser has successfully updated their profile.";
        } else {
            $logSubject = 'Profile Update Failed';
            $logMessage = "$logUser tried to update their profile, but no changes were made.";
        }

        Log::create([
            'type' => $logType,
            'user' => $logUser,
            'subject' => $logSubject,
            'message' => $logMessage,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }
}
