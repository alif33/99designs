<?php

namespace App\Http\Controllers;

use App\Mail\VerifyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function verification()
    {   
        $details = [
            'name' => 'kazi arifur rahman'
        ];
        return Mail::to('dev2kaziarif@gmail.com')
           ->send(new VerifyMail($details));
    }
}
