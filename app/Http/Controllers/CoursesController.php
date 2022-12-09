<?php

namespace App\Http\Controllers;

use App\Models\courses;
use App\Models\roles;
use App\Models\users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index() :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
            $all_courses = courses::with(['course_type', 'user'])->get();
            return response()->json([
                'status' => 'success',
                'all_courses' => $all_courses,
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
            $specific_course = courses::where('id', $request->id)
                ->with(['course_type', 'user'])
                ->first();
            return response()->json([
                'status' => 'success',
                'specific_course' => $specific_course,
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
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'course_type_id' => 'required|int'
            ]);
            $created_course = courses::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => Auth::id,
                'course_type_id' => $request->course_type_id
            ]);
            return response()->json([
                'status' => 'success',
                'created_course' => $created_course,
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
                'name' => 'string|max:255',
                'description' => 'string|max:255',
                'user_id' => 'int',
                'course_type_id' => 'int'
            ]);

            if (sizeof($fields) != 0){
                courses::where('id', $request->id)->update($fields);
            }
            $course_to_return = courses::where('id', $request->id)
                ->with(['course_type', 'user'])
                ->first();
            return response()->json([
                'status' => 'success',
                'updated_course' => $course_to_return,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }
}
