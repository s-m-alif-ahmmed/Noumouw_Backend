<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\InstructorResource;

class InstructorController extends Controller
{
    public function index()
    {
        try{
            $instructors = Instructor::all();
            return Helper::jsonResponse(true, 'Instructors data fetch successfully', 200, InstructorResource::collection($instructors));
        }catch(\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try{
            $instructor = Instructor::find($id);
            if (!$instructor) {
                return Helper::jsonErrorResponse('Instructor not found',404);
            }
            return Helper::jsonResponse(true, 'Instructor data fetch successfully', 200, new InstructorResource($instructor));
        }catch(\Exception $exception){
            return Helper::jsonErrorResponse($exception->getMessage(), 500);
        }
    }


  








}
