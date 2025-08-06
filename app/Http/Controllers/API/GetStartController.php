<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GetStart;
use App\Helpers\Helper;


class GetStartController extends Controller
{
    public function index()
    {
        try {
            $getStarts = GetStart::all();

            if (!$getStarts) {
                return Helper::jsonErrorResponse('GetStart not found', 404);
            }


            return Helper::jsonResponse(true, 'GetStart data fetch successfully', 200, $getStarts);
        } catch (\Exception $exception) {
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try{
             $getStarts = GetStart::find($id);

             if(!$getStarts){
                return Helper::jsonErrorResponse('GetStart not found', 404);
             }
             return Helper::jsonResponse(true, 'GetStart data fetch successfully', 200, $getStarts);
        }catch(\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }
}
