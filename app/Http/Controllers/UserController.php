<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePatientRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\PatientResource;
use App\Http\Resources\UserResource;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return UserResource::collection($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // if ($user->role == 'doctor') {
        //     $user = User::with(['schedules','bookings'])->where('id', $user->id)->first();
        // }

        // if ($user->role == 'patient') {
        //     $user = User::with('appointments')->where('id', $user->id)->first();
        // }

        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateUserRequest  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->fill($data);
        $user->save();

        return response()->json([
            'message' => $user ? 'patient updated successfully' : 'Error ! patient did not updated successfully',
            'User' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user = $user->delete();

        return response()->json([
            'message' => $user ? 'patient deleted successfully' : 'Error ! patient did not deleted successfully'
        ]);
    }
}
