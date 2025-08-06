<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\User;

class AcceptPushNotificationController extends Controller
{

    public function index()
    {
       try{

        $data = User::where('id', Auth::user()->id)->select('accept_push_notifications')->first();
        return Helper::jsonResponse(true, 'Push notifications status fetch successfully', 200 , $data);
       }catch(\Exception $e){

        return Helper::jsonErrorResponse($e->getMessage(), 500);

       }

    }

    public function update(Request $request)
    {

        $request->validate([
            'accept_push_notifications' => 'required|boolean',
        ]);

        try{
            $data = User::where('id', Auth::user()->id)->update(['accept_push_notifications' => $request->accept_push_notifications]);
            return Helper::jsonResponse(true, 'Push notifications updated successfully', 200 , $data);
        }catch(\Exception $e){
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }
}
