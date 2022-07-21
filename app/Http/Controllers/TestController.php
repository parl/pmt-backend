<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    //
    public function createTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "test_case" => "required|string",
            "priority" => "required|string",
            "status" => "required|string",
            "type" => "required|string",
            "attachments" => "required|file",
            "expected_result" => "required|string",
            "result" => "required|string",
            "steps_to_reproduce" => "required|string",
            "components" => "required|string",
            "description" => "required|string",
            "project_id" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        unset($fields['attachment']);
        $project = Project::where('id', $fields['project_id'])->first();
        if (!$project) {
            return response()->json(["error" => "Id Project tidak ditemukan"], 400);
        }

        if ($fields['status'] == config('constants.TEST_STATUS.OPEN') || $fields['status'] == config('constants.TEST_STATUS.TO_DO') || $fields['status'] == config('constants.TEST_STATUS.FIXED') || $fields['status'] == config('constants.TEST_STATUS.REOPEN') || $fields['status'] == config('constants.TEST_STATUS.CLOSED')) {
        } else {
            return response()->json([
                "error" => "Status salah",
                "Status tersedia" => config('constants.TEST_STATUS')
            ], 400);
        }

        $new_test = Test::create($fields);

        $file = $request->file('attachments');
        $filename = $new_test['id'] . '_' . $file->getClientOriginalName();
        $file->move(public_path('files/test'), $filename);

        $file_data = [
            'test_id' => $new_test['id'],
            'name' => $fields['test_case'],
            'url' => 'files/project/' . $new_test['id'] . $filename,
        ];
        $new_file = Attachment::create($file_data);
        return response()->json([
            "User" => $new_file,
            "Status" => "Create Test Succeed"
        ], 200);
    }

    public function getTest($projectId)
    {
        $data = Test::where('project_id', '=', $projectId)->get();
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

    public function getAttachment($testId)
    {
        $data = Attachment::where('test_id', '=', $testId)->get();
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

    public function deleteTest(Request $request, $id)
    {
        $test = Test::where('id', '=', $id)->first();
        if (!$test) {
            return response()->json(["error" => "Test tidak ditemukan"], 400);
        }
        Test::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }

    public function updateTest(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "test_case" => "string",
            "priority" => "string",
            "status" => "string",
            "type" => "string",
            "expected_result" => "string",
            "result" => "string",
            "steps_to_reproduce" => "string",
            "components" => "string",
            "description" => "string",
            "project_id" => "string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $project = Project::where('id', $fields['project_id'])->first();
        if (!$project) {
            return response()->json(["error" => "Id Project tidak ditemukan"], 400);
        }

        if ($fields['status'] == config('constants.TEST_STATUS.OPEN') || $fields['status'] == config('constants.TEST_STATUS.TO_DO') || $fields['status'] == config('constants.TEST_STATUS.FIXED') || $fields['status'] == config('constants.TEST_STATUS.REOPEN') || $fields['status'] == config('constants.TEST_STATUS.CLOSED')) {
        } else {
            return response()->json([
                "error" => "Status salah",
                "Status tersedia" => config('constants.TEST_STATUS')
            ], 400);
        }
        $test = Test::where('id', '=', $id)->first();
        if (!$test) {
            return response()->json(["error" => "Id test tidak ditemukan"], 400);
        }
        $test->update($fields);
        return response()->json([
            "data" => $test,
            "Status" => "Update Success"
        ], 200);
    }
}
