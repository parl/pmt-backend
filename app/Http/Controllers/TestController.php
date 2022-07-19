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
    public function readFile(Request $request, $id)
    {
        $filePath = 'files/test';
        // if (gettype($request->query('path')) != "NULL") {
        //     $imgPath = $filePath . '/' . $request->query('path');
        // }
        // if (!is_dir(public_path($imgPath))) {
        //     return $this->ErrorResponse('Folder tidak ditemukan', 404);
        // }
        $fileScan = scandir(public_path($filePath));
        $files = array();
        $directories = array();
        foreach ($fileScan as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_dir(public_path($filePath))) {
                array_push($directories, $file);
            } else {
                array_push($files, $file);
            }
        }
        return $this->successResponse([
            "files" => $files,
            "directories" => $directories
        ]);
    }

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



        // if ($fields['category'] == config('constants.CATEGORY.PRIORITY_1') || $fields['category'] == config('constants.CATEGORY.PRIORITY_2')) {
        // } else {
        //     return response()->json(["error" => "Category salah"], 400);
        // }

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
            "task" => "string",
            "category" => "string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
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
