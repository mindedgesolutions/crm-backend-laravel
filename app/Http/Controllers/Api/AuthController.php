<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $user = Auth::user()->with('roles')->with('userDetail')->first();
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
        return response()->json(['user' => Auth::user()], Response::HTTP_OK);
    }
}
