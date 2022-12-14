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
    public function __construct(){
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index() :JsonResponse {
        $user_details = users::query()
            ->where('id', Auth::id())
            ->first();
        $user_roles = roles::query()
            ->where('id', $user_details->role_id)
            ->first();
        if ($user_roles->read_right == 1){
            $all_evaluations = evaluations::query()
                ->with(['user', 'course', 'course.course_type'])
                ->get();
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
        $user_details = users::query()
            ->where('id', Auth::id())
            ->first();
        $user_roles = roles::query()
            ->where('id', $user_details->role_id)
            ->first();
        if ($user_roles->read_right == 1){
            $specific_evaluation = evaluations::query()
                ->where('id', $request->id)
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
        $user_details = users::query()
            ->where('id', Auth::id())
            ->first();
        $user_roles = roles::query()
            ->where('id', $user_details->role_id)
            ->first();
        if ($user_roles->create_right == 1){
            $request->validate([
                'course_id' => 'required|int'
            ]);

            $registration_token = Str::random(32);

            $created_evaluations = evaluations::query()
                ->create([
                    'token' => $registration_token,
                    'is_done' => 0,
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
        $user_details = users::query()
            ->where('id', Auth::id())
            ->first();
        $user_roles = roles::query()
            ->where('id', $user_details->role_id)
            ->first();
        if ($user_roles->update_right == 1){
            $fields = $request->validate([
                'course_id' => 'int',
                'user_id' => 'int',
                'is_done' => 'int',
                'average' => 'float'
            ]);

            if (sizeof($fields) != 0){
                evaluations::query()
                    ->where('id', $request->id)
                    ->update($fields);
            }
            $evaluation_to_return = evaluations::query()
                ->where('id', $request->id)
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
            ->where('evaluation_id', $request->id)
            ->get();
        evaluations::query()
            ->where('id', $request->id)
            ->update(array("average" => $all_answer_avg->average("meta_value"), "is_done" => 1, "user_id" => Auth::id()));
        return response()->json([
            'status' => 'success',
            'message' => "Evaluation Saved",
        ], 200);
    }

    public function getEvaluationByToken(Request $request) :JsonResponse {
        $evaluation_to_return = evaluations::query()
            ->where('token', $request->token)
            ->with(['user', 'course', 'course.course_type', 'course.user'])
            ->first();
        return response()->json([
            'status' => 'success',
            'updated_evaluation' => $evaluation_to_return,
        ], 200);
    }
}
