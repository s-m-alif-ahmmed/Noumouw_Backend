<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class SupportController extends Controller
{
    public function index()
    {
        return view('fontend.support.index');
    }

    public function send(Request $request){

        $request->validate([
            'fullName' => 'required|string|max:50',
            'email' => 'required|email',
            'country' => 'required|string',
            'message' => 'required|string',
        ]);

        //  dd($request->all());

        if($this->isOnline()) {

            $mail = [
                'recipient' => 'jalismahamud31@gmail.com',
                'fullName' => $request->fullName,
                'email' => $request->email,
                'country' => $request->country,
                'body' => $request->message,
            ];


           // Mail::to($mail)->send(new ContactMail($mail));

            Mail::send('email.support', $mail, function ($message) use ($mail) {
                    $message->to($mail['recipient'])
                             ->from($mail['email'], $mail['fullName'])
                             ->subject('Support mail from '. $mail['fullName']);
            });

            return redirect()->back()->with('success', 'Your message has been sent successfully');

        }else{
            return redirect()->back()->with('error', 'Please check your internet connection');
        }

    }

    public function isOnline($site = "http://google.com") {
      if(@fopen($site, "r")) {

        return true;
      }else{
        return false;
      }
    }
}




