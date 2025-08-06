<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NewsletterController extends Controller
{
    public function index(){

        try{
            $data = User::where('id', Auth::user()->id)->select('email_newsletter')->first();
            return Helper::jsonResponse(true, 'Email newsletter status fetch successfully', 200 , $data);
        }catch(\Exception $e){

            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }


    public function update(Request $request){

        $request->validate([
            'email_newsletter' => 'required|boolean',
        ]);

        try{
            $data = User::where('id', Auth::user()->id)->update(['email_newsletter' => $request->email_newsletter]);
            return Helper::jsonResponse(true, 'Email newsletter updated successfully', 200 , $data);
        }catch(\Exception $e){
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }
}
