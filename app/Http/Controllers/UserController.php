<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function list()
    {
        $users = User::all();
        return response()->json([
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,kol,advertiser,user',
        ]);

        // Create a new user
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);  // 201 Created
    }

    // Show details of a specific user
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    // Update a specific user
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate input data
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:admin,kol,advertiser,user',
        ]);

        // Update the user
        $user->username = $request->username;
        $user->role = $request->role;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return response()->json($user);
    }

    // Delete a specific user
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
