<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
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
        $this->authorize('view', Booking::class);
        $booking = Booking::all();
        return BookingResource::collection($booking);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BookingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookingRequest $request)
    {
        $this->authorize('create', Booking::class);

        $data = $request->validated();

        $booking = new Booking();
        $booking->fill($data);
        $booking->save();

        // update booking status in scheduals table
        $Schedule = Schedule::where('id', $data['schedule_id'])->update([
            'status' => 'booked',
        ]);

        return response()->json([
            'message' => ($booking && $Schedule) ? 'booking created successfully' : 'Error ! patient did not created successfully',
            'booking' => new BookingResource($booking)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', Booking::class);
        return new BookingResource($booking);
    }

    /**
     * Get bookings of doctor.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getBookings(Request $request)
    {
        $this->authorize('bookings', Booking::class);

        $user = $request->user();
        $schedule = Booking::where('doctor_id', $user->id)->get();
        return BookingResource::collection($schedule);
    }

    /**
     * get appoinments of patients.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getAppointments(Request $request)
    {
        $user = $request->user();
        $this->authorize('appointments', Booking::class);

        $schedule = Booking::where('patient_id', $user->id)->get();
        return BookingResource::collection($schedule);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookingRequest $request
     * @param  Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function update(BookingRequest $request, Booking $booking)
    {
        $this->authorize('update', $booking);
        $data = $request->validated();

        if($booking->schedule_id !== $data['schedule_id']){
            // new schedule id status changed to booked
            $Schedule = Schedule::find($data['schedule_id']);
            $Schedule = $Schedule->update([
                'status' => 'booked',
            ]);

            // old schedule ID status changed to available for booking
            $S = Schedule::find($booking->schedule_id);
            $S = $S->update([
                'status' => 'available',
            ]);
        }

        $booking->fill($data);
        $booking->save();

        return response()->json([
            'message' => ($booking && $Schedule) ? 'booking updated successfully' : 'Error ! patient did not updated successfully',
            'booking' => new BookingResource($booking)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', Booking::class);
        // update schedual slot status in scheduals table
        $Schedule = Schedule::where('id', $booking->schedule_id)->update([
            'status' => 'available',
        ]);

        $booking = $booking->delete();

        return response()->json([
            'message' => $booking ? 'booking deleted successfully' : 'Error ! booking did not deleted successfully'
        ]);
    }
}
