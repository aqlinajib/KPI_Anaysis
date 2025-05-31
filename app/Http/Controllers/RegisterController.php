<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Log in the user if you want to
        // Auth::login($user);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'divisi' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'divisi' => $data['divisi'],
            'tgl_expired' => $data['tgl_expired'],
            'password' => Hash::make($data['password']),
            'role' => 'user', // Set the role to 'user' by default
        ]);
    }
    public function showuser()
    {
        $user = User::all();
        return view('admin.user', compact('user'));
    }

    public function edituser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edituser', compact('user'));
    }

    public function updateuser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'divisi' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'divisi' => $request->input('divisi'),
            'tgl_expired' => $request->input('tgl_expired')
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return redirect()->route('user.show', $user->id)->with('success', 'User updated successfully.');
    }

    public function destroyuser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.show')->with('success', 'User deleted successfully.');
    }
}
