<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|email",
            "username" => "required|string",
            "password" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $user = User::where('email', $fields['email'])->first();
        if ($user) {
            return response()->json(["error" => "User Sudah Terdaftar"], 400);
        }
        $fields['password'] = bcrypt($fields['password']);
        $new_user = User::create($fields);

        return response()->json([
            "User" => $new_user,
            "Status" => "Register Berhasil"
        ], 200);
    }

    public function notAuthenticated()
    {
        return response()->json([
            "Status" => "Not Authenticated"
        ], 200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => "required|string",
            "password" => "required|string"
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $user = User::where('username', $fields['username'])->first();
        if (!$user || !Hash::check($fields['password'], $user['password'])) {
            return $this->ErrorResponse("Username atau Password salah", 400);
        };
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            "User" => $user['username'],
            "AccessToken" => $token,
            "Status" => "Login Berhasil"
        ], 200);
    }
}
