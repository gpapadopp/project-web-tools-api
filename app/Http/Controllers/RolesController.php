<?php

namespace App\Http\Controllers;

use App\Models\roles;
use App\Models\users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
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
        $user_details = users::query()
            ->where('id', Auth::id())
            ->first();
        $user_roles = roles::query()
            ->where('id', $user_details->role_id)
            ->first();
        if ($user_roles->read_right == 1){
            $specific_role = roles::query()
                ->where('id', $request->id)
                ->first();
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
}
