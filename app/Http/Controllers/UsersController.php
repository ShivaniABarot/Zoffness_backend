<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();  // You can use pagination if needed
        return view('users.list', compact('users'));
    }

    public function getUsers(Request $request)
{
    if ($request->ajax()) {
        $users = User::select(['id', 'firstname', 'lastname', 'email', 'phone_no', 'created_at']);

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return $row->firstname . ' ' . $row->lastname;
            })
            ->addColumn('actions', function($row) {
                $viewBtn = '<a href="'.route('users.show', $row->id).'" class="btn btn-sm" title="View"><i class="bx bx-show"></i></a>';
                $editBtn = '<a href="'.route('users.edit', $row->id).'" class="btn btn-sm" title="Edit"><i class="bx bx-edit"></i></a>';
                $deleteBtn = '<button class="btn btn-sm" onclick="deleteUser('.$row->id.')" title="Delete"><i class="bx bx-trash"></i></button>';
                return $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}


    // Show the form for creating a new user (create)
    public function create()
    {
        return view('users.create');
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_no' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
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
    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string',
    //         // 'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|unique:users,email,' . $id,
    //         'password' => 'nullable|string|min:8|confirmed',
    //         'role' => 'required|in:admin,tutor,parent',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('users.edit', $id)
    //                          ->withErrors($validator)
    //                          ->withInput();
    //     }

    //     $user = User::findOrFail($id);
    //     // $user->name = $request->name;
    //     $user->username = $request->username;
    //     $user->email = $request->email;
    //     $user->role = $request->role;

    //     if ($request->filled('password')) {
    //         $user->password = Hash::make($request->password);
    //     }

    //     $user->save();

    //     return redirect()->route('users')->with('success', 'User updated successfully.');
    // }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_no' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;

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
        $user = $request->user();
        // dd(4545 , $user );

        return response()->json([
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'phone_no' => $user->phone_no,
            // 'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'id'=>$user->id,
        ]);
    }


    public function store_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_no' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'user' => $user
        ], 201);
    }


    public function update_api(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_no' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'user' => $user
        ]);
    }



    // public function store_api(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //         'role' => 'required|in:admin,tutor,parent',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $user = User::create([
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => $request->role,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User created successfully!',
    //         'user' => $user  // Optional: include created user data
    //     ], 201);
    // }


    // public function update_api(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email,' . $id,
    //         'password' => 'nullable|string|min:8|confirmed',
    //         'role' => 'required|in:admin,tutor,parent',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $user = User::findOrFail($id);
    //     $user->username = $request->username;
    //     $user->email = $request->email;
    //     $user->role = $request->role;

    //     if ($request->filled('password')) {
    //         $user->password = Hash::make($request->password);
    //     }

    //     $user->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User updated successfully!',
    //         'user' => $user  // Optional: include updated user data
    //     ]);
    // }


}
