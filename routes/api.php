<?php

use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

//
//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

require __DIR__.'/api_auth.php';


//! support api routes
// Route::post('/support/mail',[App\Http\Controllers\API\SupportController::class,'supportMail']);

Route::get('/private-video/{path}', function ($path) {
    // Ensure the user is authenticated
    if (!auth('sanctum')->check()) {
        abort(403, 'Unauthorized access.');
    }

    // Decode URL if necessary
    $path = urldecode($path);

    // Check if the file exists in the private storage
    if (!Storage::disk('private')->exists($path)) {
        abort(404, 'File not found.');
    }
    // Retrieve the file's full path
    $fullPath = Storage::disk('private')->path($path);

    // Stream the file securely
    return new StreamedResponse(function () use ($fullPath) {
        $stream = fopen($fullPath, 'rb');
        while (!feof($stream)) {
            echo fread($stream, 8192); // Stream in chunks of 8 KB
            ob_flush();
            flush();
        }
        fclose($stream);
    }, 200, [
        'Content-Type' => Storage::disk('private')->mimeType($path),
        'Content-Length' => Storage::disk('private')->size($path),
        'Accept-Ranges' => 'bytes', // Support partial requests for streaming
    ]);
})->where('path', '.*')->name('private.video');


Route::middleware('auth:sanctum')->get('/test-push', function () {
    // $usertoken = FirebaseToken::where('user_id', auth()->id())->first();    
    // $token = $usertoken->token;
    $fcmService = new FCMService();
    // dd($fcmService);
    $fcmService->sendMessage('fuwN0-4Mg0gKoELWBFCC4h:APA91bH9jBy9zBDPwQs12dD245ValkPt8nvqtUwH7BV9QsmKhq41R93JN20YGvVqd7jik04PgnYOW48hTOxxdkTTmKyAp69VFwWGs9VM1lPosHYFjzupI-I', 'Test Push', 'success');
    return "Success";
});