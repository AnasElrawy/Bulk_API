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
        


           // Validation
           $validated = $request->validate([
            'numbers' => 'required|array|min:1', 
            'numbers.*.phoneNumber' => ['required', 'regex:/^201[0-9]{9}$/'],
            'numbers.*.senderName' => 'required|string|max:255', 
            'message' => 'required|string|min:5', 
        ], [
            // Custom error messages
            'numbers.required' => 'Please add at least one phone number.',
            'numbers.*.phoneNumber.required' => 'Each phone number is required.',
            'numbers.*.phoneNumber.regex' => 'Each phone number must be 12 digit and in the format 201*********.',
            'numbers.*.senderName.required' => 'Each sender name is required.',
            'numbers.*.senderName.max' => 'Sender name cannot exceed 255 characters.',
            'message.required' => 'Message content is required.',
            'message.min' => 'The message must be at least 10 characters.',
            'sender.required' => 'The sender name is required.',
        ]);

        // After validation, process the data
        $numbers = $validated['numbers'];
        $message = $validated['message'];


        foreach ($numbers as $number) {

            $phoneNumber = $number['phoneNumber'];
            $SenderName = $number['senderName'];
            $operatorID = $this->detectOperator($phoneNumber);


            try {


                    
                // Send the API request
                $response = Http::withHeaders([
                    'Authorization' => 'c1d7fd97-3587-41e8-853a-e9cb91176197',
                    'Content-Type' => 'application/json',
                ])->post('https://hub.advansystelecom.com/generalapiv12/api/bulkSMS/ForwardSMS',
                  [
                    'PhoneNumber' => $phoneNumber,
                    'Message' => $message,
                    'SenderName' => $SenderName,
                    'RequestID' => '1',
                    'OperatorID' => 1,
                  ]

                  );
              
                $responseCode = $response->json(); 
                //   Log the request and response
                SMSLog::create([
                    'sender_name' => $SenderName,
                    'message' => $message,
                    'phone_number' =>  $phoneNumber,
                    'status' => $responseCode,
                ]);
               
            } catch (Exception $e) {
                // Log or collect the error for this number
                $errors[] = [
                    'number' => $number,
                    'error' => $e->getMessage()
                ];
            }
        }
    }


    private function detectOperator($phoneNumber)
    {
        // Check the prefix of the phone number and return the corresponding operator ID
        if (substr($phoneNumber, 0, 4) == '2010') {
            return 1; // Vodafone
        } elseif (substr($phoneNumber, 0, 4) == '2012') {
            return 2; // Orange
        } elseif (substr($phoneNumber, 0, 4) == '2011') {
            return 3; // Etisalat
        } elseif (substr($phoneNumber, 0, 4) == '2015') {
            return 7; // WE
        }

        return 0; // Unknown operator
    }
}
