<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function validateUser(Request $request)
    {
        $data = User::get();
        if ($data) {
            return response([
                "user" => "true"
            ]);
        } else {
            return response([
                "user" => "false"
            ]);
        }
    }
}
