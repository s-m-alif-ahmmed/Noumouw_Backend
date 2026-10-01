<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Helper;
use App\Models\SupportContact;

class SupportController extends Controller
{
    public function supportMail(Request $request)
    {


        $request->validate([
            // 'user_id' => 'required|exists:users,id',
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
                $mail->to($request->email)
                    ->subject('New Support Request');
            });
            SupportContact::create([
                'user_id' => auth()->id(),
                'name' => $request->fullName,
                'email' => $request->email,
                'country' => $request->country,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            return Helper::jsonResponse(true, 'Email sent successfully', 200);
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }
}
