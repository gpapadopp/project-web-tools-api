<?php

namespace App\Http\Controllers;

use App\Models\course_types;
use App\Models\roles;
use App\Models\users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseTypesController extends Controller
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
            $all_course_types = course_types::all();
            return response()->json([
                'status' => 'success',
                'all_courses_types' => $all_course_types,
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
            $specific_course_type = course_types::query()
                ->where('id', $request->id)
                ->first();
            return response()->json([
                'status' => 'success',
                'specific_course_type' => $specific_course_type,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }
}
