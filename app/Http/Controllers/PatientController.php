<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Http\Resources\PatientResource;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patient = Patient::all();
        return $patient;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PatientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientRequest $request)
    {
        $data = $request->validated();
        
        $patient = new Patient;
        $patient->fill($data);
        $patient->save();

        return new PatientResource($patient);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = Patient::where('id', $id)->get();
        return $patient;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdatePatientRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePatientRequest $request, $id)
    {
        $data = $request->validated();
        $patient = Patient::where('id', $id)
            ->update($data);

        return response()->json([ 
            'message' => $patient ? 'patient updated successfully' : 'Error ! patient did not updated successfully' 
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient = Patient::where('id', $id)->delete();

        return response()->json([ 
            'message' => $patient ? 'patient deleted successfully' : 'Error ! patient did not deleted successfully' 
        ]);
    }
}
