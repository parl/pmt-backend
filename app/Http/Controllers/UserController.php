<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getAllUser(Request $request)
    {
        $data = User::get();
        if ($data) {
            return response()->json([
                "data" => $data,
                "Status" => "Success"
            ], 200);
        } else {
            return response()->json([
                "Status" => "Failed"
            ], 400);
        }
    }
    public function getUserById($id)
    {
        $data = User::where('id', '=', $id)->first();;
        if ($data) {
            return response()->json([
                "data" => $data,
                "Status" => "Success"
            ], 200);
        } else {
            return response()->json([
                "Status" => "Failed"
            ], 400);
        }
    }
    public function deleteInternalBriefing($id)
    {
        $user = User::where('id', '=', $id)->first();
        if (!$user) {
            return response()->json(["error" => "User tidak ditemukan"], 400);
        }
        User::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string",
            "email" => "email",
            "username" => "string",
            "password" => "string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $user = User::where('id', '=', $id)->first();
        if (!$user) {
            return response()->json(["error" => "Id user tidak ditemukan"], 400);
        }
        $user->update($fields);
        return response()->json([
            "data" => $user,
            "Status" => "Update Success"
        ], 200);
    }
}
