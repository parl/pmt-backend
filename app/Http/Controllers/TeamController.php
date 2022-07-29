<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User_team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    //
    public function createTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "user" => "array",
            "user.*" => "uuid"
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

        if ($fields['user']) {
            $user = $fields['user'];
            $data = [];
            foreach ($user as $pengguna) {
                $now = Carbon::now()->toDateTimeString();
                $member_id = (string) Str::uuid();
                array_push($data, [
                    "id" => $member_id,
                    "team_id" => $new_team['id'],
                    "user_id" => $pengguna,
                    "created_at" => $now,
                    "updated_at" => $now
                ]);
            }
            User_team::insert($data);
            // for ($i = 0; $i < count($user); $i++) {
            //     $data = [
            //         'team_id' => $new_team['id'],
            //         'user_id' => $user[$i]
            //     ];
            // }
        }
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

    public function getAllTeam()
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

    public function getTeam($id)
    {
        // $team_member = User_team::where('team_id', '=', $id)->get();
        $team_member = DB::table('user_teams')
            ->join('users', 'users.id', '=', 'user_teams.user_id')
            ->join('teams', 'teams.id', '=', 'user_teams.team_id')
            ->select('teams.id as id_team', 'teams.name as nama_team', 'user_teams.id as member_id', 'user_teams.user_id', 'users.name as nama_user')
            ->where('user_teams.team_id', '=', $id)
            ->get();
        if ($team_member) {
            return response()->json([
                "data" => $team_member,
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
            "name" => "string",
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
