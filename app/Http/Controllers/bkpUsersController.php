<?php

namespace App\Http\Controllers;

use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            // Only fetch users where the role is "admin"
            $users = User::select('id', 'name', 'email', 'role', 'created_at')
                // ->where('role', 'admin')  // Filter users by "admin" role
                ->get();  // Get the filtered data

            return datatables()->of($users)
                ->addColumn('action', function ($user) {
                    $editButton = '<button class="btn btn-outline-primary btn-sm" onclick="editUser(' . $user->id . ')">
                    <i class="fas fa-edit"></i></button>';

                    $deleteButton = '<button class="btn btn-outline-danger btn-sm" onclick="deleteUser(' . $user->id . ')">
                      <i class="fas fa-trash-alt"></i></button>';

                    $viewButton = '<a href="' . route('users.show', $user->id) . '" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-eye"></i>
                    </a>';


                    return $editButton . ' ' . $deleteButton . ' ' . $viewButton;
                })

                ->addIndexColumn()
                ->make(true);
        }

        return view('users.list');
    }

    // Edit and update
// Edit User (for modal)
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Return user data in a JSON response
        return response()->json(['user' => $user]);
    }

    // Handle the update request
// Handle Update
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|max:255',
        ]);

        // Find and update the user
        $user = User::findOrFail($id);
        $user->update($request->only('username', 'email', 'role'));

        // Return a response indicating success (or error)
        return response()->json(['success' => true]);
    }


    public function store(Request $request)
    {
        // Set a default value for the 'role' if not provided
        $role = $request->role ?? 'admin';

        // Validate the incoming request
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|string|in:admin',  // Ensures role is only 'admin'
        ]);

        // Create the new user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'role' => $role,  // Set the role (default to 'admin' if not provided)
        ]);

        // Return a success response
        return response()->json(['success' => true]);
    }



    // Handle delete
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete user.']);
        }
    }

    // View function
    public function show($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Return the view with user details
        return view('users.view', compact('user'));
    }

}
