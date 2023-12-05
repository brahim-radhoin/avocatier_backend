<?php

namespace App\Http\Controllers;

use App\Models\Avo;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController;
use App\Mail\Verification;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Notifications\VerifyEmail;



class AvoAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:avo', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        $avo = Avo::where('email', $credentials['email'])->first();
        
        if ($avo && $avo->email_verified_at === null) {
            return response()->json(['error' => 'S\'il vous plaît, attendez que nous vérifions votre compte.'], 401);
        }
    
        if (!$token = auth::guard('avo')->attempt($credentials)) {
            return response()->json(['error' => 'L\'email ou le mot de pass ne sont pas corrects.'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated Avo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth::guard('avo')->user());
    }

    /**
     * Log the avo out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth::guard('avo')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth::guard('avo')->refresh());
    }


    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:avos',
            'password' => ['required', 'string', Password::min(8)->mixedCase()->symbols()],
        ]);

        $avo = Avo::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $admin = Admin::first();
        $adminEmail = $admin->email;

        Mail::to($adminEmail)->send(new Verification($avo));

        $token = Auth::guard('avo')->login($avo);

        return response()->json([
            'status' => 'success',
            'message' => 'Avo created successfully',
            'avo' => $avo,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('avo')->factory()->getTTL() * 60
        ]);
    }
}