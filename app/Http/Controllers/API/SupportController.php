<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Helper;

class SupportController extends Controller
{
   public function supportMail(Request $request)
{


    $request->validate([
        'fullName' => 'required|string|max:100',
        'email' => 'required|email',
        'country' => 'required|string',
        'message' => 'required|string',
    ]);

    try {
        Mail::send('email.support', [
            'fullName' => $request->fullName ?? 'N/A',
            'email' => $request->email ?? 'N/A',
            'country' => $request->country ?? 'N/A',
            'body' => $request->message ?? 'N/A',
        ], function ($mail) use ($request) {
            $mail->to('jalismahamud31@gmail.com')
                ->subject('New Support Request');
        });

        return Helper::jsonResponse(true, 'Email sent successfully', 200);
    } catch (\Exception $exception) {
        return Helper::jsonErrorResponse($exception->getMessage(), 500);
    }
}



}
