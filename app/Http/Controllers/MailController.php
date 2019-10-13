<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// mails
use App\Mail\UserRegistered;

class MailController extends Controller
{
    
    static function send_mail_user_registered($username, $email){
        Mail::to($email)
                ->send(
                    new UserRegistered($username, $email)
                );
    }

}
