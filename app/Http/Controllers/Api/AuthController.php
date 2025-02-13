<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            '*.required' => ':Attribute is required',
            'email.email' => 'Email is invalid',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['errors' => 'Incorrect credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::whereId(Auth::id())->with('roles')->with('userDetail')->first();
        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ], Response::HTTP_OK);
    }

    // ------------------------------------------------------------------------

    public function register(Request $request) {}

    // ------------------------------------------------------------------------

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
    }

    // ------------------------------------------------------------------------

    public function currentUser()
    {
        $user = User::whereId(Auth::id())->with('roles')->with('userDetail')->first();

        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    // ------------------------------------------------------------------------

    public function updateProfile(UserProfileRequest $request)
    {
        User::findOrFail(Auth::id())->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        UserDetail::where('user_id', Auth::id())->update([
            'slug' => Str::slug($request->name),
            'mobile' => $request->mobile,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Profile updated'], Response::HTTP_OK);
    }
}
