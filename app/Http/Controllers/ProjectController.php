<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    //
    public function createproject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "description" => "required|string",
            "id_team" => "required|uuid",
            "PIC_id" => "required|uuid",
            "start_date" => "required|string",
            "end_date" => "string|nullable",
        ]);

        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $fields['start_date'] = date('Y-m-d H:i:s', $fields['start_date']);
        // return response()->json([
        //     "User" => $fields['start_date'],
        //     "Status" => "Create Project Succeed"
        // ], 200);
        if ($fields['end_date']) {
            $fields['end_date'] = date('Y-m-d H:i:s', $fields['end_date']);
        }
        $project = Project::where('name', $fields['name'])->first();
        if ($project) {
            return response()->json(["error" => "Nama project Sudah digunakan"], 400);
        }
        $new_project = Project::create($fields);

        return response()->json([
            "User" => $new_project,
            "Status" => "Create Project Succeed"
        ], 200);
    }

    public function getproject()
    {
        $users = DB::table('projects')
            ->join('users', 'users.id', '=', 'projects.PIC_id')
            ->join('teams', 'teams.id', '=', 'projects.id_team')
            ->select('projects.*', 'users.name as PIC_name', 'teams.name as team_name')
            ->get();
        $data = Project::get();
        if ($users) {
            return response()->json([
                "data" => $users,
                "Status" => "Success"
            ], 200);
        } else {
            return response()->json([
                "Status" => "Failed"
            ], 400);
        }
    }

    public function deleteproject(Request $request, $id)
    {
        $project = Project::where('id', '=', $id)->first();
        if (!$project) {
            return response()->json(["error" => "Project tidak ditemukan"], 400);
        }
        Project::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }

    public function updateproject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string",
            "description" => "string",
            "id_team" => "uuid",
            "PIC_id" => "uuid",
            "start_date" => "string",
            "end_date" => "string",
            "progress" => "string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $fields['start_date'] = date('Y-m-d H:i:s', $fields['start_date']);
        if ($fields['end_date']) {
            $fields['end_date'] = date('Y-m-d H:i:s', $fields['end_date']);
        }
        $name = Project::where('name', $fields['name'])->first();
        if ($name) {
            return response()->json(["error" => "Nama project Sudah digunakan"], 400);
        }
        $project = Project::where('id', '=', $id)->first();
        if (!$project) {
            return response()->json(["error" => "Id project tidak ditemukan"], 400);
        }
        $project->update($fields);
        return response()->json([
            "data" => $project,
            "Status" => "Update Success"
        ], 200);
    }
}
