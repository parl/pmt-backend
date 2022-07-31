<?php

namespace App\Http\Controllers;

use App\Models\AssignedTo;
use App\Models\Developing;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DevelopingController extends Controller
{
    //
    public function createdeveloping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "task_id" => "required|uuid",
            "name" => "required|string",
            "start_date" => "required|string",
            "priority" => "required|string",
            "status" => "required|string",
            "end_date" => "string|nullable",
            "user" => "array",
            "user.*" => "uuid"
        ]);

        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $fields['start_date'] = date('Y-m-d H:i:s', $fields['start_date']);

        if ($fields['end_date']) {
            $fields['end_date'] = date('Y-m-d H:i:s', $fields['end_date']);
        }

        if ($fields['status'] == config('constants.DEV_STATUS.WORK_IN_PROGRESS') || $fields['status'] == config('constants.DEV_STATUS.DONE') || $fields['status'] == config('constants.DEV_STATUS.PENDING') || $fields['status'] == config('constants.STATUS.CANCELED')) {
        } else {
            return response()->json([
                "error" => "Status salah",
                "Status tersedia" => config('constants.DEV_STATUS')
            ], 400);
        }

        if ($fields['priority'] == config('constants.CATEGORY.PRIORITY_1') || $fields['priority'] == config('constants.CATEGORY.PRIORITY_2')) {
        } else {
            return response()->json([
                "error" => "Priority salah",
                "Priority tersedia" => config('constants.CATEGORY')
            ], 400);
        }
        $developing = Developing::where('name', '=', $fields['name'])->where('task_id', '=', $fields['task_id'])->first();
        if ($developing) {
            return response()->json(["error" => "Nama Developing sudah digunakan"], 400);
        }
        $new_developing = Developing::create($fields);

        // foreach ($fields['user'] as $users){
        //     $cekUser = Project::select('User_teams.nama')
        //     ->join('internal_briefings', 'internal_briefings.id', '=', 'developings.task_id')
        //     ->where('internal_briefings.project_id', '=', $p->id)
        //     ->get();
        // }

        if ($fields['user']) {
            $user = $fields['user'];
            $data = [];
            foreach ($user as $pengguna) {
                $now = Carbon::now()->toDateTimeString();
                $member_id = (string) Str::uuid();
                array_push($data, [
                    "id" => $member_id,
                    "developing_id" => $new_developing['id'],
                    "user_id" => $pengguna,
                    "created_at" => $now,
                    "updated_at" => $now
                ]);
            }
            AssignedTo::insert($data);
        }

        return response()->json([
            "User" => $new_developing,
            "Status" => "Create Developing Succeed"
        ], 200);
    }

    public function getdeveloping($id)
    {
        $developing = Developing::where('task_id', '=', $id)->first();
        $dev_member = AssignedTo::select('assigned_tos.id as member_id', 'assigned_tos.user_id', 'users.name as nama_user')
            ->join('users', 'users.id', '=', 'assigned_tos.user_id')
            ->join('developings', 'developings.id', '=', 'assigned_tos.developing_id')
            ->where('developings.developing_id', '=', $developing->$id)
            ->get();
        if ($developing && $dev_member) {
            return response()->json([
                "data" => ['Developing' => $developing, 'Member' => $dev_member],
                "Status" => "Success"
            ], 200);
        } else {
            return response()->json([
                "Status" => "Failed"
            ], 400);
        }
    }

    public function deletedeveloping($id)
    {
        $developing = Developing::where('id', '=', $id)->first();
        if (!$developing) {
            return response()->json(["error" => "Developing tidak ditemukan"], 400);
        }
        Developing::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }

    public function updatedeveloping(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "task_id" => "uuid",
            "start_date" => "string",
            "end_date" => "string",
            "priority" => "string",
            "status" => "string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $fields['start_date'] = date('Y-m-d H:i:s', $fields['start_date']);
        if ($fields['end_date']) {
            $fields['end_date'] = date('Y-m-d H:i:s', $fields['end_date']);
        }

        $name = Developing::where('name', $fields['name'])->first();
        if ($name) {
            return response()->json(["error" => "Nama developing Sudah digunakan"], 400);
        }
        $developing = Developing::where('id', '=', $id)->first();
        if (!$developing) {
            return response()->json(["error" => "Id developing tidak ditemukan"], 400);
        }
        $developing->update($fields);
        return response()->json([
            "data" => $developing,
            "Status" => "Update Success"
        ], 200);
    }
}
