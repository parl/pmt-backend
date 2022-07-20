<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    //
    public function createTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $team = Team::where('name', $fields['name'])->first();
        if ($team) {
            return response()->json(["error" => "Nama team Sudah digunakan"], 400);
        }
        $new_team = Team::create($fields);

        return response()->json([
            "User" => $new_team,
            "Status" => "Create Team Succeed"
        ], 200);
    }

    public function getTeamById($id)
    {
        $data = Team::where('id', '=', $id)->first();;
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

    public function getTeam()
    {
        $data = Team::get();
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

    public function deleteTeam(Request $request, $id)
    {
        $team = Team::where('id', '=', $id)->first();
        if (!$team) {
            return response()->json(["error" => "Team tidak ditemukan"], 400);
        }
        Team::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }

    public function updateTeam(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $name = Team::where('name', $fields['name'])->first();
        if ($name) {
            return response()->json(["error" => "Nama team Sudah digunakan"], 400);
        }
        $team = Team::where('id', '=', $id)->first();
        if (!$team) {
            return response()->json(["error" => "Id team tidak ditemukan"], 400);
        }
        $team->update($fields);
        return response()->json([
            "data" => $team,
            "Status" => "Update Success"
        ], 200);
    }
}
