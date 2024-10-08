<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function users()
    {
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('created_at', 'ASC')
            ->paginate(5);
        return view('admin.users.index', compact('users'));
    }


    public function addUser()
    {
        return view('admin.users.addUser');
    }

    public function validateAddUserForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveUser(Request $request)
    {
        $userData = [
            'name' => trim($request->name),
            'username' => trim($request->username),
            'email' => trim($request->email),
            'password' => trim(Hash::make($request->password)),
            'level' => 'Admin',
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $createUser = User::create($userData);

        $user = Auth::user()->username;

        if ($createUser) {
            $this->logAddUserSuccess($user, $request->username);
            return redirect()->route('users')->with('success', 'User Added Successfully!');
        } else {
            $this->logAddUserFailed($user, $request->username);
            return redirect()->route('users')->with('failed', 'Failed to Add User!');
        }
    }

    private function logAddUserSuccess($user, $username)
    {
        Log::create([
            'type' => 'Add User',
            'user' => $user,
            'subject' => 'Add User Success',
            'message' => "$user has successfully added $username as an admin.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logAddUserFailed($user, $username)
    {
        Log::create([
            'type' => 'Add User',
            'user' => $user,
            'subject' => 'Add User Failed',
            'message' => "$user has unsuccessfully added $username as an admin.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function editUser(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.editUser', compact('user'));
    }

    public function validateEditUserForm(Request $request, string $id)
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

    public function updateUser(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($this->shouldUpdateUserDetails($user, $request)) {
            $this->updateUserDetails($user, $request);

            $this->logUpdate($user);

            return redirect()
                ->route('users.editUser', $user->id)
                ->with('success', 'User Updated Successfully!');
        } else {
            return redirect()
                ->route('users.editUser', $user->id)
                ->with('failed', 'Please Edit a Field!');
        }
    }

    private function shouldUpdateUserDetails(User $user, Request $request)
    {
        return (
            $request->input('name') !== $user->name ||
            $request->input('username') !== $user->username ||
            $request->input('email') !== $user->email ||
            $request->filled('password')
        );
    }

    private function updateUserDetails(User $user, Request $request)
    {
        $user->name = $request->filled('name') ? $request->name : $user->name;
        $user->username = $request->filled('username') ? $request->username : $user->username;
        $user->email = $request->filled('email') ? $request->email : $user->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->updated_at = now('Asia/Manila');
        $user->update();
    }

    private function logUpdate(User $user)
    {
        $logType = 'Edit User';
        $logUser = Auth::user()->username;

        if ($user->wasChanged()) {
            $logSubject = 'Edit User Success';
            $logMessage = "$logUser has successfully updated $user->username's details.";
        } else {
            $logSubject = 'Edit User Failed';
            $logMessage = "$logUser has unsuccessfully updated $user->username's details.";
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

    public function deleteUser(string $id)
    {
        try {
            DB::beginTransaction();

            $admin = User::findOrFail($id);
            $user = Auth::user()->username;

            $this->createUserDeleteLog($user, $admin);

            $admin->delete();

            DB::commit();

            return redirect()->route('users')->with('success', 'User Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->createUserDeleteLog($user, $admin, 'Failed to delete User', $e->getMessage());

            return redirect()->route('users')->with('failed', 'Failed to delete User!');
        }
    }

    private function createUserDeleteLog($user, $admin, $subject = 'Delete User Success', $errorMessage = null)
    {
        $logData = [
            'type' => 'Delete User',
            'user' => $user,
            'subject' => $subject,
            'message' => "$admin->username has been " . strtolower($subject) . " by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        if ($errorMessage) {
            $logData['message'] .= ' Error: ' . $errorMessage;
        }

        Log::create($logData);
    }
}
