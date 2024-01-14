<?php

namespace App\Http\Controllers;

use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "full_name" => "required|string|max:50|unique:users",
            "email" => "required|string|max:50|unique:users",
            "user_name" => [
                "required",
                "string",
                "max:50",
                "regex:/^[^\s]+$/u",
                "unique:users",
            ],
            "password" => [
                "required",
                "string",
                "min:8",
                "regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/"],
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 401);
        }
        $user = new User;
        $user->full_name = $request->input("full_name");
        $user->user_name = $request->input("user_name");
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->count = 0;

        $user->save();

        $user->refresh();

        $user = Auth::user();

        return $user;
    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("user_name", "password"))) {
            return response([
                "message" => "Invalid credentials!",
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'message' => 'Success',
        ])->withCookie($cookie);
    }
    public function user()
    {
        return Auth::user();
    }
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }
    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response(['message' => 'Success'])->withCookie($cookie);
    }
    public function changeAvatar(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$request->hasFile('avatar')) {
            return response()->json(['message' => 'No avatar file provided'], 400);
        }

        $user->avatar = Cloudinary::upload($request->file('avatar')->getRealPath())->getSecurePath();
        $user->save();

        return response()->json(['message' => 'Avatar changed successfully']);
    }
}
