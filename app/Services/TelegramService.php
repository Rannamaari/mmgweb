<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Booking;

class TelegramService
{
    private string $botToken;
    private string $chatId;

    public function __construct()
    {
        $this->botToken = '7846097297:AAEEvm6psj5QBncPV-8vb9Xbup1321WhNU0';
        $this->chatId = '298353051';
    }

    public function sendBookingNotification(Booking $booking): bool
    {
        $message = $this->formatBookingMessage($booking);

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function sendStatusUpdate(Booking $booking): bool
    {
        $message = $this->formatStatusUpdateMessage($booking);

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Telegram status update failed: ' . $e->getMessage());
            return false;
        }
    }

    private function formatBookingMessage(Booking $booking): string
    {
        $pickupInfo = $booking->pickup_needed ? "📍 Pickup needed: {$booking->pickup_address}" : "📍 No pickup needed";

        return "
🚨 <b>NEW BOOKING RECEIVED!</b>

👤 <b>Customer:</b> {$booking->name}
📞 <b>Phone:</b> {$booking->phone}
📧 <b>Email:</b> " . ($booking->email ?: 'Not provided') . "

🏍️ <b>Bike Details:</b>
• Make: {$booking->bike_make}
• Model: {$booking->bike_model}
• Year: " . ($booking->bike_year ?: 'Not specified') . "
• Plate: " . ($booking->plate_number ?: 'Not specified') . "

🔧 <b>Service:</b> {$booking->service_type}
📅 <b>Date:</b> {$booking->preferred_date->format('d/m/Y')}
⏰ <b>Time:</b> {$booking->preferred_time}

{$pickupInfo}

📝 <b>Issue Description:</b>
" . ($booking->issue_description ?: 'No description provided') . "

🆔 <b>Booking ID:</b> #{$booking->id}
        ";
    }

    private function formatStatusUpdateMessage(Booking $booking): string
    {
        return "
📊 <b>BOOKING STATUS UPDATED</b>

🆔 <b>Booking ID:</b> #{$booking->id}
👤 <b>Customer:</b> {$booking->name}
📞 <b>Phone:</b> {$booking->phone}

🔄 <b>New Status:</b> {$booking->status_label}
💰 <b>Cost:</b> {$booking->formatted_cost}

" . ($booking->admin_notes ? "📝 <b>Notes:</b> {$booking->admin_notes}" : "") . "
        ";
    }
}
