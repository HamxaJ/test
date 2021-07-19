<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PatientResource;
use App\Http\Requests\PatientRequest;
use App\Models\Patient;

class AuthController extends Controller
{
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('accessToken')->accessToken;
            return response()->json([
                'patient' => new PatientResource(auth()->user()),
                'token' => $token
            ], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PatientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(PatientRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $patient = new Patient;
        $patient->fill($data);
        $patient->save();

        $token = $patient->createToken('accessToken')->accessToken;
 
        return response()->json([
            'patient' => new PatientResource($patient),
            'token' => $token
        ], 200);
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
