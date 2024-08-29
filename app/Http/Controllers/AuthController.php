<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field(s)',
                'errors' => $validator->errors()
            ], 422);
        }

        $auth = Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if(!$auth) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = auth()->user()->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'token' => $token
        ]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|unique:users,username|regex:/^[A-Za-z0-9._]+$/',
            'email' => 'required',
            'password' => 'required',
            'dob' => 'required',
            'phone' => 'required',
            'profile' => 'required|mimes:png,jpg,jpeg',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field(s)',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        $data['role'] = 'user';
        $data['dob'] = Carbon::parse($data['dob']);
        
        if($request->hasFile('profile')) {
            $file = $request->file('profile');
            $file->move(public_path() . "/profile/$request->username/", "image.png");
        }
        $data['profile'] = "/profile/$request->username/image.png";
        
        $user = User::create($data);

        return response()->json([
            'message' => 'User created successfully',
            'user' => collect($user)->only(['name','username','email','dob','phone'])
        ]);
    }

    
    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => "Logout success"
        ]);
    }

    public function profile() {
        $user = auth()->user();

        return response()->json([
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'dob' => $user->dob,
            'profile' => asset($user->profile),
            'phone' => $user->phone,
            'role' => $user->role,
        ]);
    }

    public function update(Request $request) {
        $user = auth()->user();
        
        if($request->hasFile('profile')) {
            $file = $request->file('profile');
            $file->move(public_path() . "/profile/$request->username/", "image.png");
        }

        $user->update($request->all());

        return response()->json([
            'message' => 'User updated'
        ]);
    }
}
