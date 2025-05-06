<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    // Display the user list (read)
    public function index()
    {
        $users = User::all();  // You can use pagination if needed
        return view('users.list', compact('users'));
    }

    // Show the form for creating a new user (create)
    public function create()
    {
        return view('users.create');
    }

    // Store a newly created user (create)
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,tutor,parent',
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('users.create')
                             ->withErrors($validator)
                             ->withInput();
        }
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
    
        return redirect()->route('users')->with('success', 'User created successfully.');
    }
    
    // Show the form for editing the specified user (update)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update the specified user (update)
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,tutor,parent',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.edit', $id)
                             ->withErrors($validator)
                             ->withInput();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }


    public function show($id)
    {
        $user = User::findOrFail($id);  // This will automatically throw a 404 error if the user is not found.
        return view('users.view', compact('user'));
    }
    
    
    // Remove the specified user (delete)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
    
        // Delete related records in the `tutors` table
        $user->users()->delete();
    
        // Delete the user
        $user->delete();
    
        return redirect()->route('users')->with('success', 'User deleted successfully.');
    }
    
}
