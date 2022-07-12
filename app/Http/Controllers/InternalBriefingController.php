<?php

namespace App\Http\Controllers;

use App\Models\InternalBriefing;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InternalBriefingController extends Controller
{
    //
    public function createInternalBriefing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "task" => "required|string",
            "category" => "required|string",
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
        if ($fields['category'] == config('constants.CATEGORY.PRIORITY_1') || $fields['category'] == config('constants.CATEGORY.PRIORITY_2')) {
        } else {
            return response()->json(["error" => "Category salah"], 400);
        }
        $new_internalBriefing = InternalBriefing::create($fields);
        return response()->json([
            "User" => $new_internalBriefing,
            "Status" => "Create InternalBriefing Succeed"
        ], 200);
    }

    public function getInternalBriefing($projectId)
    {
        $data = InternalBriefing::where('project_id', '=', $projectId)->get();
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

    public function deleteInternalBriefing(Request $request, $id)
    {
        $internalBriefing = InternalBriefing::where('id', '=', $id)->first();
        if (!$internalBriefing) {
            return response()->json(["error" => "InternalBriefing tidak ditemukan"], 400);
        }
        InternalBriefing::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }

    public function updateInternalBriefing(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "task" => "string",
            "category" => "string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $internalBriefing = InternalBriefing::where('id', '=', $id)->first();
        if (!$internalBriefing) {
            return response()->json(["error" => "Id internalBriefing tidak ditemukan"], 400);
        }
        $internalBriefing->update($fields);
        return response()->json([
            "data" => $internalBriefing,
            "Status" => "Update Success"
        ], 200);
    }
}
