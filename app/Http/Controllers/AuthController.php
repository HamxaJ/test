<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PatientResource;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register', 'forgotPassword', 'resetPassword']);
    }

    /**
     * Handles Login Request
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('accessToken')->accessToken;
            return response()->json([
                'user' => new UserResource(auth()->user()),
                'token' => $token
            ], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = new User();
        $user->fill($data);
        $user->save();

        $token = $user->createToken('accessToken')->accessToken;

        return response()->json([
            'patient' => new UserResource($user),
            'token' => $token
        ], 200);
    }

    /**
     * Forgot Password for User
     *
     */

    public function forgotPassword(PasswordRequest $request)
    {
        $email = $request->validated();

        if (!User::where('email', $email)->exists()) {
            return response()->json([
                'message' => 'Patient with this email does not exist',
            ], 404);
        }

        $resetPassword = Password::sendResetLink( $email );

        return response()->json([
            'message' => 'An email having passwort reset link is being sent to you. Please check your email'
        ]);
    }

     /**
     * Reset Password for User
     *
     */

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();
        $data['token'] = $request->token;

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);

    }

    /**
     * Logout User from auth.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
