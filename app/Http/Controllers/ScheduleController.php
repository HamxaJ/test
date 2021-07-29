<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;

class ScheduleController extends Controller
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
        $schedule = Schedule::all();
        return ScheduleResource::collection($schedule);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ScheduleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScheduleRequest $request)
    {
        $this->authorize('create', Schedule::class);
        $data = $request->validated();
        $schedule['user_id'] = $request->user()->id;

        $duration = CarbonPeriod::create('tomorrow', '1 day', 7);

        foreach($duration as $item){
            $schedule['date'] = $item->format('Y-m-d H:i:s');
            $schedule['weekday'] = $item->format('l');
            $key = $item->dayOfWeek;

            if (array_key_exists($key,$data['weekdays'])) {
                $day_start = $data['weekdays'][$key]['start_time'];
                $day_end = $data['weekdays'][$key]['end_time'];

                // making time slots for single day
                $period = new CarbonPeriod($day_start, '30 minutes', $day_end); // for create use 24 hours format later change format

                foreach($period as $item){
                    $schedule['start_time'] = $item->format('Y-m-d H:i:s');
                    $schedule['end_time'] = $item->addMinutes(30)->format('Y-m-d H:i:s');
                    $schedule['status'] = 'available';

                    $sdule = new Schedule();
                    $sdule->fill($schedule);
                    $sdule->save();
                }
            }

        }
        return response()->json([
            'message' => 'Schedule added successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        return new ScheduleResource($schedule);
    }

    /**
     * Update the specified resource(schedule slot) in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $data = $request->validated();

        $schedule = new Schedule;
        $schedule = $schedule->fill($data);

        return response()->json([
            'message' => $schedule ? 'patient updated successfully' : 'Error ! patient did not updated successfully',
            $schedule,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ScheduleRequest $request
     * @param Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function updateDoctorSchedule(ScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        $data = $request->validated();

        $slot['user_id'] = $request->user()->id;

        $duration = CarbonPeriod::create('tomorrow', '1 day', 7);
        foreach($duration as $item){
            $slot['date'] = $item->format('Y-m-d H:i:s');
            $slot['weekday'] = $item->format('l');
            $key = $item->dayOfWeek;

            if (array_key_exists($key,$data['weekdays'])) {
                $day_start = $data['weekdays'][$key]['start_time'];
                $day_end = $data['weekdays'][$key]['end_time'];

                $delete = Schedule::where('user_id', $slot['user_id'])->where('weekday', $slot['weekday'])->delete();
                if ($delete) {
                    dd('working fine', $delete);
                }
                // making time slots for single day
                $period = new CarbonPeriod($day_start, '30 minutes', $day_end); // for create use 24 hours format later change format

                foreach($period as $item){
                    $slot['start_time'] = $item->format('Y-m-d H:i:s');
                    $slot['end_time'] = $item->addMinutes(30)->format('Y-m-d H:i:s');
                    $slot['status'] = 'available';

                    $slot_record = new Schedule();
                    $slot_record->fill($slot);
                    $slot_record->save();
                }
            }

        }
        return response()->json([
            'message' => 'Schedule added successfully'
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule = $schedule->delete();

        return response()->json([
            'message' => $schedule ? 'patient deleted successfully' : 'Error ! patient did not deleted successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */

    public function getSchedule(User  $user){
        $schedule = Schedule::where('user_id', $user->id)->where('status', 'available')->get();
        return ScheduleResource::collection($schedule);
    }
}
