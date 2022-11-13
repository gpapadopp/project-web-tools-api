<?php

namespace App\Http\Controllers;

use App\Models\evaluations_meta;
use App\Models\roles;
use App\Models\users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationsMetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index() :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
            $all_evaluations_meta = evaluations_meta::with(['evaluation'])->get();
            return response()->json([
                'status' => 'success',
                'all_evaluations_meta' => $all_evaluations_meta,
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
            $specific_evaluation_meta = evaluations_meta::where('id', $request->id)
                ->with(['evaluation'])
                ->first();
            return response()->json([
                'status' => 'success',
                'specific_evaluation_meta' => $specific_evaluation_meta,
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
                'evaluation_id' => 'required|int',
                'meta_key' => 'required|string|max:255',
                'meta_value' => 'required|string|max:255',
            ]);

            $created_evaluations_meta = evaluations_meta::create([
                'evaluation_id' => $request->evaluation_id,
                'meta_key' => $request->meta_key,
                'meta_value' => $request->meta_value
            ]);
            return response()->json([
                'status' => 'success',
                'created_evaluations_meta' => $created_evaluations_meta,
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
                'evaluation_id' => 'int',
                'meta_key' => 'string|max:255',
                'meta_value' => 'string|max:255',
            ]);

            if (sizeof($fields) != 0){
                evaluations_meta::where('id', $request->id)->update($fields);
            }
            $evaluation_meta_to_return = evaluations_meta::where('id', $request->id)
                ->with(['evaluation'])
                ->first();
            return response()->json([
                'status' => 'success',
                'updated_evaluation_meta' => $evaluation_meta_to_return,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }
}
