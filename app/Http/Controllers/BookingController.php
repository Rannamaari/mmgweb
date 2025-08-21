<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function show()
    {
        return view('booking');
    }

    public function store(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Booking submission received', [
            'data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'service_type' => 'required|string|max:255',
            'bike_make' => 'required|string|max:255',
            'bike_model' => 'required|string|max:255',
            'bike_year' => 'nullable|string|max:255',
            'plate_number' => 'nullable|string|max:255',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|string',
            'pickup_needed' => 'nullable|boolean',
            'pickup_address' => 'nullable|string',
            'issue_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Booking validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Please check your input and try again.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $booking = Booking::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'service_type' => $request->service_type,
                'bike_make' => $request->bike_make,
                'bike_model' => $request->bike_model,
                'bike_year' => $request->bike_year,
                'plate_number' => $request->plate_number,
                'preferred_date' => $request->preferred_date,
                'preferred_time' => $request->preferred_time,
                'pickup_needed' => $request->boolean('pickup_needed') ? 'true' : 'false',
                'pickup_address' => $request->pickup_address,
                'issue_description' => $request->issue_description,
                'status' => 'pending',
            ]);

            Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'customer_name' => $booking->name
            ]);

            // Send Telegram notification
            try {
                $telegramService = new TelegramService();
                $telegramSuccess = $telegramService->sendBookingNotification($booking);

                Log::info('Telegram notification sent', [
                    'booking_id' => $booking->id,
                    'success' => $telegramSuccess
                ]);
            } catch (\Exception $e) {
                Log::error('Telegram notification failed', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the booking if Telegram fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking submitted successfully! We will contact you shortly to confirm your appointment.',
                'booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error processing your booking. Please try again or call us at 9996210.'
            ], 500);
        }
    }

    // Test method to verify booking works without CSRF
    public function testStore(Request $request)
    {
        return $this->store($request);
    }
}
