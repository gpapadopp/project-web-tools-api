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
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index() :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
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
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
            $specific_course_type = course_types::where('id', $request->id)->first();
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

    public function add(Request $request) :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['create_right'] == 1){
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'disabled' => 'required|int',
            ]);
            $created_course_type = course_types::create([
                'name' => $request->name,
                'description' => $request->description,
                'disabled' => $request->disabled
            ]);
            return response()->json([
                'status' => 'success',
                'created_course_type' => $created_course_type,
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
                'disabled' => 'int'
            ]);

            if (sizeof($fields) != 0){
                course_types::where('id', $request->id)->update($fields);
            }
            $course_type_to_return = course_types::where('id', $request->id)->first();
            return response()->json([
                'status' => 'success',
                'updated_course_type' => $course_type_to_return,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    public function delete(Request $request) :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['delete_right'] == 1){
            course_types::where('id', $request->id)->update(array('disabled' => 1));
            return response()->json([
                'status' => 'success',
                'message' => "Course Type Deleted",
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }
}
