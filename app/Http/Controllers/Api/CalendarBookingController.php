<?php

namespace App\Http\Controllers\Api;
use App\Models\UserServicePackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CalendarBookingController extends Controller
{
    public function coachCalendarBookingDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'coach_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $coach_id = $request->input('coach_id');

            $booking = UserServicePackage::where('coach_id', $coach_id)
                        ->get();

            if ($booking->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No service package found'
                ], 404);
            }

            // Prepare response with selected fields
            $data = $booking->map(function($item) {
                return [
                    'id' => $item->id,
                    'coach_id' => $item->coach_id,
                    'title' => $item->title,
                    'booking_slots' => $item->booking_slots,
                    'booking_availability' => $item->booking_availability,
                    'booking_window' => $item->booking_window,
                    // 'user' => [
                    //     'id' => $item->user->id ?? null,
                    //     'first_name' => $item->user->first_name ?? null,
                    //     'last_name' => $item->user->last_name ?? null,
                    // ]
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'All bookings of services package coach',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
