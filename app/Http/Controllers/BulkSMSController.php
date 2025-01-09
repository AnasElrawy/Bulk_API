<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use App\Models\SMSLog;

class BulkSMSController extends Controller
{
    //

    public function send(Request $request)
    {
        dd($request);

        // dd('aa');
        // $validated = $request->validate([
        //     'PhoneNumber' => 'required|string',
        //     'Message' => 'required|string',
        //     'SenderName' => 'required|string',
        //     'OperatorID' => 'required|integer',
        // ]);

        try {
            // Send the API request
            $response = Http::withHeaders([
                'Authorization' => 'c1d7fd97-3587-41e8-853a-e9cb91176197',
                'Content-Type' => 'application/json',
            // ])->post('https://hub.advansystelecom.com/generalapiv12/api/bulkSMS/ForwardSMS', $validated);
            ])->post('https://hub.advansystelecom.com/generalapiv12/api/bulkSMS/ForwardSMS',
        
            [
                'PhoneNumber' => '201090388845,201090388835',
                'Message' => 'Welcome to our service!',
                'SenderName' => 'UESystems',
                'RequestID' => '1',
                'OperatorID' => 1,
            ]
        
            );

            $responseCode = $response->json();

            // // Log the request and response
            // SMSLog::create([
            //     'phone_number' => $validated['PhoneNumber'],
            //     'message' => $validated['Message'],
            //     'sender_name' => $validated['SenderName'],
            //     'operator_id' => $validated['OperatorID'],
            //     'request_id' => $validated['RequestID'],
            //     'response_code' => $responseCode,
            // ]);
            if ($responseCode == 1) {
                return back()->with('success', 'Message sent successfully!');
            } else {
                // Handle specific error codes
                $errorMessages = [
                    -1 => 'Invalid authorization token.',
                    -2 => 'Empty mobile number.',
                    -3 => 'Empty message.',
                    -4 => 'Invalid sender.',
                    -5 => 'No credit available for account.',
                ];
            
                $errorMessage = $errorMessages[$responseCode] ?? 'An unknown error occurred.';
                return back()->with('error', "Failed to send message. Error: {$errorMessage}");
            }
            
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
