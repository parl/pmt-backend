<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequirementController extends Controller
{
    //
    public function createRequirement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "requirement" => "required|string",
            "project_id" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $project = Project::where('id', $fields['project_id'])->first();
        if (!$project) {
            return response()->json(["error" => "Id Project tidak ditemukan"], 400);
        }
        $new_requirement = Requirement::create($fields);

        return response()->json([
            "User" => $new_requirement,
            "Status" => "Create Requirement Succeed"
        ], 200);
    }

    public function getRequirement($projectId)
    {
        $data = Requirement::where('project_id', '=', $projectId)->get();
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

    public function deleteRequirement(Request $request, $id)
    {
        $requirement = Requirement::where('id', '=', $id)->first();
        if (!$requirement) {
            return response()->json(["error" => "Requirement tidak ditemukan"], 400);
        }
        Requirement::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }

    public function updateRequirement(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "requirement" => "required|string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $requirement = Requirement::where('id', '=', $id)->first();
        if (!$requirement) {
            return response()->json(["error" => "Id requirement tidak ditemukan"], 400);
        }
        $requirement->update($fields);
        return response()->json([
            "data" => $requirement,
            "Status" => "Update Success"
        ], 200);
    }
}
