<?php

namespace App\Http\Controllers;

use App\Models\InternalBriefing;
use App\Models\Project;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    //
    public function createReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "task_id" => "required|string",
            "status" => "required|string",
            "type" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        $task = InternalBriefing::where('id', $fields['task_id'])->first();
        if (!$task) {
            return response()->json(["error" => "Id Task tidak ditemukan"], 400);
        }
        if ($fields['status'] == config('constants.STATUS.DONE') || $fields['status'] == config('constants.STATUS.TO_DO') || $fields['status'] == config('constants.STATUS.CANCELED')) {
        } else {
            return response()->json([
                "error" => "Status salah",
                "Status tersedia" => config('constants.STATUS')
            ], 400);
        }
        if ($fields['type'] == config('constants.REVIEW_TYPE.REVIEW_INTERNAL') || $fields['type'] == config('constants.REVIEW_TYPE.REVIEW_EKSTERNAL')) {
        } else {
            return response()->json([
                "error" => "Type salah",
                "Status tersedia" => config('constants.REVIEW_TYPE')
            ], 400);
        }
        $new_review = Review::create($fields);

        return response()->json([
            "User" => $new_review,
            "Status" => "Create Review Succeed"
        ], 200);
    }

    public function getReview($taskId)
    {
        $data = Review::where('task_id', '=', $taskId)->get();
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

    public function getReviewById($Id)
    {
        $data = Review::where('id', '=', $Id)->get();
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

    public function deleteReview(Request $request, $id)
    {
        $review = Review::where('id', '=', $id)->first();
        if (!$review) {
            return response()->json(["error" => "Review tidak ditemukan"], 400);
        }
        Review::where('id', '=', $id)->delete();
        return response()->json([
            "data" => null,
            "Status" => "Success"
        ], 200);
    }

    public function updateReview(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "status" => "string",
        ]);
        if ($validator->fails()) {
            return response()->json(["data" => $validator->errors()], 400);
        };
        $fields = $validator->validated();
        if ($fields['status'] == config('constants.STATUS.DONE') || $fields['status'] == config('constants.STATUS.TO_DO') || $fields['status'] == config('constants.STATUS.CANCELED')) {
        } else {
            return response()->json([
                "error" => "Status salah",
                "Status tersedia" => config('constants.STATUS')
            ], 400);
        }
        $review = Review::where('id', '=', $id)->first();
        if (!$review) {
            return response()->json(["error" => "Id review tidak ditemukan"], 400);
        }
        $review->update($fields);
        return response()->json([
            "data" => $review,
            "Status" => "Update Success"
        ], 200);
    }
}
