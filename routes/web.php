<?php

use App\Jobs\UserJob;
use App\Mail\UserMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/send-user-mail',function ()
{
    //Sending a plain text email using Laravel Mail::raw() method
//    Mail::raw('This is a test mail', function ($message) {
//       
//        $message->to('xyz@gmail.com', 'Test User')->subject('Test Mail');
//    });
    
    //Sending an HTML email using Laravel Mail::send() method
//    Mail::send('welcome', [], function ($message) {
//       
//        $message->to('xyz@gmail.com', 'Test User')->subject('Test Mail');
//    });

    //Sending an HTML view and Attachments email using Laravel Mail::send() method (Mail Class)
    //Mail::send(new UserMail());
    
    //dispatch or queue a job for background processing. 
    UserJob::dispatch();
    
    return 'Mail sent successfully';
});