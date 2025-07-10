<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
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
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,tutor,parent',
        ]);
    
        if ($validator->fails()) {
            // If it's an AJAX request, return JSON errors
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
    
            return redirect()->route('users.create')
                             ->withErrors($validator)
                             ->withInput();
        }
    
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
    
        // If it's an AJAX request, return success JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully!'
            ]);
        }
    
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
            'username' => 'required|string',
            // 'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,tutor,parent',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.edit', $id)
                             ->withErrors($validator)
                             ->withInput();
        }

        $user = User::findOrFail($id);
        // $user->name = $request->name;
        $user->username = $request->username;
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
    
        $user->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
    

    // show user details API 01-07-25

    public function showProfile(Request $request)
    {
        $user = $request->user(); // gets the logged-in user

        return response()->json([
            'username' => $user->username,
            'email' => $user->email,
            'role'=>$user->role,
        ]);
    }


    public function store_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,tutor,parent',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'user' => $user  // Optional: include created user data
        ], 201);
    }
    

    public function update_api(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'username' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'password' => 'nullable|string|min:8|confirmed',
        'role' => 'required|in:admin,tutor,parent',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::findOrFail($id);
    $user->username = $request->username;
    $user->email = $request->email;
    $user->role = $request->role;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'User updated successfully!',
        'user' => $user  // Optional: include updated user data
    ]);
}

    
}
