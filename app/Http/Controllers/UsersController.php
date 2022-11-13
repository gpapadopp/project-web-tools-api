<?php

namespace App\Http\Controllers;

use App\Mail\SendUserVerificationEmail;
use App\Models\roles;
use App\Models\users;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register', 'verify']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials, false);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => \auth('api')->attempt($credentials),
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request) :JsonResponse {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'disabled' => 'required|int',
            'role_id' => 'required|int'
        ]);

        $registration_token = Str::random(32);

        $created_user = users::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'username' => $request->username,
            'password' => '-',
            'disabled' => $request->disabled,
            'role_id' => $request->role_id,
            'locked' => 1,
            'token' => $registration_token,
            'email' => $request->email
        ]);
        $this->sendEmail($request->email, $registration_token);
        return response()->json([
            'status' => 'success',
            'added_user' => $created_user,
        ], 201);
    }

    public function index() :JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
            $all_users = users::with(['role'])->get();
            return response()->json([
                'status' => 'success',
                'all_users' => $all_users,
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    public function specific(Request $request): JsonResponse {
        $user_details = users::where('id', Auth::id())->first();
        $user_roles = roles::where('id', $user_details['role_id'])->first();
        if ($user_roles['read_right'] == 1){
            $specific_user = users::where('id', $request->id)->with(['role'])->first();
            return response()->json([
                'status' => 'success',
                'specific_users' => $specific_user,
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
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'disabled' => 'required|int',
                'role_id' => 'required|int'
            ]);

            $registration_token = Str::random(32);

            $created_user = users::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'username' => $request->username,
                'password' => '-',
                'disabled' => $request->disabled,
                'role_id' => $request->role_id,
                'locked' => 1,
                'token' => $registration_token,
                'email' => $request->email
            ]);
            $this->sendEmail($request->email, $registration_token);
            return response()->json([
                'status' => 'success',
                'added_user' => $created_user,
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
                'first_name' => 'string|max:255',
                'last_name' => 'string|max:255',
                'phone' => 'string|max:255',
                'username' => 'string|max:255',
                'password' => 'string|max:255',
                'email' => 'string|max:255',
                'disabled' => 'int',
                'role_id' => 'int'
            ]);

            if (sizeof($fields) !=0){
                users::where('id', $request->id)->update($fields);
            }
            $user_to_return = users::where('id', $request->id)->with(['role'])->first();
            return response()->json([
                'status' => 'success',
                'updated_user' => $user_to_return,
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
            users::where('id', $request->id)->update(array('disabled' => 1));
            return response()->json([
                'status' => 'success',
                'message' => 'User Deleted',
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    public function verify(Request $request) :JsonResponse {
        $request->validate([
            'password' => 'required|string|max:255',
            'token' => 'required|string|max:255'
        ]);
        users::where('token', $request->token)->update(array(
            'password' => Hash::make($request->password),
            'locked' => 0
        ));
        return response()->json([
            'status' => 'success',
            'message' => 'User Confirmed Successfully',
        ], 200);
    }

    private function sendEmail($emailTo, $token) :void {
        Mail::to($emailTo)->send(new SendUserVerificationEmail($token));
    }
}
