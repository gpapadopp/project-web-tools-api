<?php

namespace App\Http\Controllers;

use App\Models\roles;
use App\Models\users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function index() :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
            $all_roles = roles::all();
            return response()->json([
                'status' => 'success',
                'all_roles' => $all_roles,
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
            $specific_role = roles::where('id', $request->id)->first();
            return response()->json([
                'status' => 'success',
                'specific_role' => $specific_role,
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
                'create_right' => 'required|int',
                'read_right' => 'required|int',
                'update_right' => 'required|int',
                'delete_right' => 'required|int',
                'super_admin' => 'required|int',
                'disabled' => 'required|int',
            ]);
            $created_role = roles::create([
                'name' => $request->name,
                'description' => $request->description,
                'create_right' => $request->create_right,
                'read_right' => $request->read_right,
                'update_right' => $request->update_right,
                'delete_right' => $request->delete_right,
                'super_admin' => $request->super_admin,
                'disabled' => $request->disabled
            ]);
            return response()->json([
                'status' => 'success',
                'created_role' => $created_role,
            ], 200);
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
                'create_right' => 'int',
                'read_right' => 'int',
                'update_right' => 'int',
                'delete_right' => 'int',
                'super_admin' => 'int',
                'disabled' => 'int',
            ]);
            if (sizeof($fields) != 0) {
                roles::where('id', $request->id)->update($fields);
            }
            $role_to_return = roles::where('id', $request->id)->first();
            return response()->json([
                'status' => 'success',
                'updated_role' => $role_to_return,
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
            roles::where('id', $request->id)->update(array('disabled' => 1));
            return response()->json([
                'status' => 'success',
                'message' => 'Role Deleted',
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }
}
