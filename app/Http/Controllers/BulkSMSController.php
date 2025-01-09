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
        
        // dd('aa');
        // $validated = $request->validate([
            //     'PhoneNumber' => 'required|string',
            //     'Message' => 'required|string',
            //     'SenderName' => 'required|string',
            //     'OperatorID' => 'required|integer',
            // ]);
            
        $numbers = $request->input('numbers');
        $message = $request->input('message');
            

        foreach ($numbers as $number) {

            $phoneNumber = $number['phoneNumber'];
            $SenderName = $number['senderName'];
            $operatorID = $this->detectOperator($phoneNumber);
            // dd($phoneNumber);


            try {


                    
                // Send the API request
                $response = Http::withHeaders([
                    'Authorization' => 'c1d7fd97-3587-41e8-853a-e9cb91176197',
                    'Content-Type' => 'application/json',
                  // ])->post('https://hub.advansystelecom.com/generalapiv12/api/bulkSMS/ForwardSMS', $validated);
                ])->post('https://hub.advansystelecom.com/generalapiv12/api/bulkSMS/ForwardSMS',
                  [
                    'PhoneNumber' => $phoneNumber,
                    'Message' => $message,
                    // 'SenderName' => 'UESystems',
                    'SenderName' => $SenderName,
                    'RequestID' => '1',
                    'OperatorID' => 1,
                  ]

                  );
              
                $responseCode = $response->json(); 
                // dd($responseCode);   
                //   Log the request and response
                SMSLog::create([
                    'sender_name' => $SenderName,
                    'message' => $message,
                    'phone_number' =>  $phoneNumber,
                    'status' => $responseCode,
                ]);
                
                  // // Log the request and response
                  // SMSLog::create([
                  //     'phone_number' => $validated['PhoneNumber'],
                  //     'message' => $validated['Message'],
                  //     'sender_name' => $validated['SenderName'],
                  //     'operator_id' => $validated['OperatorID'],
                  //     'request_id' => $validated['RequestID'],
                  //     'response_code' => $responseCode,
                  // ]);
                //   if ($responseCode == 1) {
                //       return back()->with('success', 'Message sent successfully!');
                //   } else {
                //       // Handle specific error codes
                //       $errorMessages = [
                //           -1 => 'Invalid authorization token.',
                //           -2 => 'Empty mobile number.',
                //           -3 => 'Empty message.',
                //           -4 => 'Invalid sender.',
                //           -5 => 'No credit available for account.',
                //       ];
                    
                //       $errorMessage = $errorMessages[$responseCode] ?? 'An unknown error occurred.';
                //       return back()->with('error', "Failed to send message. Error: {$errorMessage}");
                //   }
                

                // Example SMS sending logic (replace with your actual API call)
                // Assuming sendSmsToNumber is a method that handles the SMS API
                // $this->sendSmsToNumber($number, $request->input('message'));
            } catch (Exception $e) {
                // Log or collect the error for this number
                $errors[] = [
                    'number' => $number,
                    'error' => $e->getMessage()
                ];
            }
        }

        // dd($numbers[0]['phoneNumber']);

        // try {
        //     // Send the API request
        //     $response = Http::withHeaders([
        //         'Authorization' => 'c1d7fd97-3587-41e8-853a-e9cb91176197',
        //         'Content-Type' => 'application/json',
        //     // ])->post('https://hub.advansystelecom.com/generalapiv12/api/bulkSMS/ForwardSMS', $validated);
        //     ])->post('https://hub.advansystelecom.com/generalapiv12/api/bulkSMS/ForwardSMS',
        
        //     [
        //         'PhoneNumber' => $numbers[0]['phoneNumber'],
        //         'Message' => $message,
        //         'SenderName' => 'UESystems',
        //         'RequestID' => '1',
        //         'OperatorID' => 1,
        //     ]
        
        //     );

        //     $responseCode = $response->json();
        //     dd($responseCode);   

        //     // // Log the request and response
        //     // SMSLog::create([
        //     //     'phone_number' => $validated['PhoneNumber'],
        //     //     'message' => $validated['Message'],
        //     //     'sender_name' => $validated['SenderName'],
        //     //     'operator_id' => $validated['OperatorID'],
        //     //     'request_id' => $validated['RequestID'],
        //     //     'response_code' => $responseCode,
        //     // ]);
        //     if ($responseCode == 1) {
        //         return back()->with('success', 'Message sent successfully!');
        //     } else {
        //         // Handle specific error codes
        //         $errorMessages = [
        //             -1 => 'Invalid authorization token.',
        //             -2 => 'Empty mobile number.',
        //             -3 => 'Empty message.',
        //             -4 => 'Invalid sender.',
        //             -5 => 'No credit available for account.',
        //         ];
            
        //         $errorMessage = $errorMessages[$responseCode] ?? 'An unknown error occurred.';
        //         return back()->with('error', "Failed to send message. Error: {$errorMessage}");
        //     }
            
        // } catch (\Exception $e) {
        //     return back()->with('error', 'An error occurred: ' . $e->getMessage());
        // }
    }


    private function detectOperator($phoneNumber)
    {
        // Check the prefix of the phone number and return the corresponding operator ID
        if (substr($phoneNumber, 0, 5) == '02010') {
            return 1; // Vodafone
        } elseif (substr($phoneNumber, 0, 5) == '02012') {
            return 2; // Orange
        } elseif (substr($phoneNumber, 0, 5) == '02011') {
            return 3; // Etisalat
        } elseif (substr($phoneNumber, 0, 4) == '2015') {
            return 7; // WE
        }

        return 0; // Unknown operator
    }
}
