<?php

namespace App\Http\Services;

use App\Http\Services\Sms\SendService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class SmsServices
{
    # send sms
    public function sendSMS($to, $text, $from = null)
    {
        if (getSetting('active_sms_gateway') == 'twilio') {

            $TWILIO_SID = env('TWILIO_SID');
            $TWILIO_AUTH_TOKEN = env('TWILIO_AUTH_TOKEN');

            try {
                Http::withHeaders([
                    'Authorization' => 'Basic ' . \base64_encode("$TWILIO_SID:$TWILIO_AUTH_TOKEN")
                ])->asForm()->post("https://api.twilio.com/2010-04-01/Accounts/$TWILIO_SID/Messages.json", [
                            "Body" => $text,
                            "From" => env('VALID_TWILIO_NUMBER'),
                            "To" => $to,
                        ]);
            } catch (Exception $e) {
                // dd($e);
            }
        }
    }

    # phone verification

    public function phoneVerificationSms($to, $code)
    {
        // Static OTP for specific phone numbers
        $cleanTo = str_replace('+', '', $to);
        if ($cleanTo == '998943015415' || $cleanTo == '998943015498' || $cleanTo == '998943015458' || $cleanTo == '998985666666') {
            $code = '111111';
        }

        $sms = "Fondex.uz mobil ilovasi uchun tasdiqlash kodi/ Код подтверждения: $code";
        $service = new SendService();

        try {
            $service->sendSms($to, $sms);
            Log::info('OTP sent successfully', ['phone' => $to, 'otp' => $code]);
        } catch (\Exception $e) {
            Log::error('OTP sending failed', [
                'phone' => $to,
                'otp' => $code,
                'error' => $e->getMessage()
            ]);
        }
    }


    # forgot password
    public function forgotPasswordSms($to, $code)
    {
        $sms = 'Your password reset code for ' . env('APP_NAME') . ' is ' . $code . '.';
        $this->sendSMS($to, $sms);
    }
}
