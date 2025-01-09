<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    return view('send_sms');
});

use App\Http\Controllers\BulkSMSController;

Route::post('/send-sms', [BulkSMSController::class, 'send'])->name('send.sms');
