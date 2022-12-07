<?php

namespace App\Http\Controllers;

use App\Models\evaluations;
use App\Models\evaluations_meta;
use App\Models\roles;
use App\Models\users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EvaluationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index() :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
            $all_evaluations = evaluations::with(['user', 'course', 'course.course_type'])->get();
            return response()->json([
                'status' => 'success',
                'all_evaluations' => $all_evaluations,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    public function specific(Request $request) :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
            $specific_evaluation = evaluations::where('id', $request->id)
                ->with(['user', 'course', 'course.course_type'])
                ->first();
            return response()->json([
                'status' => 'success',
                'specific_evaluation' => $specific_evaluation,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    public function add(Request $request) :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['create_right'] == 1){
            $request->validate([
                'course_id' => 'required|int',
                'user_id' => 'required|int',
            ]);

            $registration_token = Str::random(32);

            $created_evaluations = evaluations::create([
                'token' => $registration_token,
                'is_done' => 0,
                'user_id' => $request->user_id,
                'course_id' => $request->course_id
            ]);
            return response()->json([
                'status' => 'success',
                'created_evaluations' => $created_evaluations,
            ], 201);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    public function update(Request $request) :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['update_right'] == 1){
            $fields = $request->validate([
                'course_id' => 'int',
                'user_id' => 'int',
                'is_done' => 'int',
                'average' => 'float'
            ]);

            if (sizeof($fields) != 0){
                evaluations::where('id', $request->id)->update($fields);
            }
            $evaluation_to_return = evaluations::where('id', $request->id)
                ->with(['user', 'course', 'course.course_type'])
                ->first();
            return response()->json([
                'status' => 'success',
                'updated_evaluation' => $evaluation_to_return,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    public function finishEvaluation(Request $request) :JsonResponse {
        //Get Answer AVG
        $all_answer_avg = evaluations_meta::query()
            ->average("meta_value")
            ->where('evaluation_id', $request->id)
            ->get();
        evaluations::query()
            ->where('id', $request->id)
            ->update(array("average" => $all_answer_avg, "is_done" => 1, "user_id" => Auth::id()));
        return response()->json([
            'status' => 'success',
            'message' => "Evaluation Saved",
        ], 200);
    }

    public function getEvaluationByToken(Request $request) :JsonResponse {
        $evaluation_to_return = evaluations::where('token', $request->token)
            ->with(['user', 'course', 'course.course_type', 'course.user'])
            ->first();
        return response()->json([
            'status' => 'success',
            'updated_evaluation' => $evaluation_to_return,
        ], 200);
    }
}
