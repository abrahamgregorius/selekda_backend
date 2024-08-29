<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::get();

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required',
            'dob' => 'required',
            'phone' => 'required',
            'profile' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field(s)',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        $data['dob'] = Carbon::parse($data['dob']);
        
        if($request->hasFile('profile')) {
            $file = $request->file('profile');
            $file->move(public_path() . "/profile/$request->username/", "image.png");
        }

        $data['profile'] = "/profile/$request->username/image.png";

        $user = User::create($data);
        $user['profile'] = asset($user['profile']);

        return response()->json([
            'message' => 'User created',
            'user' => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'User updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => "User deleted" 
        ]);
    }
}
