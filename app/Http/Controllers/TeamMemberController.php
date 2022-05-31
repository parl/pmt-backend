<?php

namespace App\Http\Controllers;

use App\Models\User_team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamMemberController extends Controller
{
    //
    public function createTeamMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "team_id" => "required|uuid",
            "user_id" => "required|uuid",
        ]);

        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $teamId = User_team::where('team_id', $fields['team_id'])->first();
        $userId = User_team::where('user_id', $fields['user_id'])->first();
        if ($teamId && $userId) {
            return response()->json(["error" => "anggota sudah terdaftar pada tim tersebut"], 400);
        }
        $new_teamUser = User_team::create($fields);

        return response()->json([
            "User" => $new_teamUser,
            "Status" => "Create Team Succeed"
        ], 200);
    }

    public function getTeamMember()
    {
        $data = User_team::get();
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

    public function deleteTeamMember(Request $request, $id)
    {
        $team = User_team::where('id', '=', $id)->first();
        if (!$team) {
            return response()->json(["error" => "Team member tidak ditemukan"], 400);
        }
        User_team::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }
}
