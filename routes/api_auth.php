<?php

use App\Helpers\VideoStream;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\ContentController;
use App\Http\Controllers\API\PodcastController;
use App\Http\Controllers\API\ActivityController;
use App\Http\Controllers\API\GetStartController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\EvaluationController;
use App\Http\Controllers\API\InstructorController;
use App\Http\Controllers\API\NewsletterController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\FirebaseTokenController;
use App\Http\Controllers\API\SubscriptionPlanController;
use App\Http\Controllers\API\AcceptPushNotificationController;
use App\Http\Controllers\API\RatingsController;
use App\Http\Controllers\API\RevenueCatController;
use App\Http\Controllers\API\SupportController;
use App\Http\Controllers\API\VideoController;
use App\Models\DynamicPage;
use App\Models\Video;
use Illuminate\Http\Request;

//! Dynamic pages - 
Route::get('dynamic-page/{slug}', function($slug) {  
    return response()->json(DynamicPage::where('page_slug', $slug)->first());
});



//! Get Start Message Route
Route::get('get-start', [GetStartController::class, 'index']);
Route::get('get-start/{id}', [GetStartController::class, 'show']);

Route::middleware(['guest'])->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('resend_otp', [RegisterController::class, 'resend_otp']);
    Route::post('verify_email', [RegisterController::class, 'verify_email']);
    Route::post('forgot-password', [RegisterController::class, 'forgot_password']);
    Route::post('verify-otp', [RegisterController::class, 'verify_otp']);
    Route::post('reset-password', [RegisterController::class, 'reset_password']);
});


Route::middleware('auth:sanctum')->group(function () {

    //! support api routes
    Route::post('/contact/support', [SupportController::class, 'supportMail']);

    //logout
    Route::post('logout', [LoginController::class, 'logout']);

    //profile routes
    Route::get('user', [LoginController::class, 'user']);
    Route::get('user-tag/list', [LoginController::class, 'userTags']);
    Route::post('profile-update', [LoginController::class, 'profile_update']);
    Route::delete('profile/delete', [LoginController::class, 'profileDelete']);

    //tag routes
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tag/{id}',[TagController::class,'show']);

    //accept push notification
    Route::get('/accept-push-notification', [AcceptPushNotificationController::class, 'index']);
    Route::post('/update-push-notification', [AcceptPushNotificationController::class, 'update']);

    //accept email newsletter
    Route::get('/email-newsletter', [NewsletterController::class, 'index']);
    Route::post('/update-email-newsletter', [NewsletterController::class, 'update']);

    //Instructor Routes
    Route::get('instructors',[InstructorController::class,'index']);
    Route::get('instructor/{id}',[InstructorController::class,'show']);

    //Course Routes
    Route::get('courses',[CourseController::class,'index']);
    Route::get('course/plan', [CourseController::class, 'course_subscription_plan']);
    Route::get('course/subscribed-course', [CourseController::class, 'getSubscribedCourses']);
    Route::delete('course/unsubscribe/{id}', [CourseController::class, 'unsubscribe']);
//    Route::get('search-course', [CourseController::class, 'searchCourse']);
    Route::get('/progress', [CourseController::class, 'progress']);
    Route::get('course/{id}',[CourseController::class,'show'])->whereNumber('id');

    //Subscribe to course
    Route::post('course/subscribe', [CourseController::class, 'subscribe']);

    //Content Routes
    Route::get('contents', [ContentController::class, 'index']);
    Route::get('content/{id}', [ContentController::class, 'show']);
    Route::post('content/completion/{id}', [ContentController::class, 'completion']);

    //Activity Routes
    Route::get('activities', [ActivityController::class, 'index']);
    Route::get('activity/{id}', [ActivityController::class, 'show']);

    //Podcast Routes
    Route::get('podcasts', [PodcastController::class, 'index']);
    Route::get('podcast/{id}', [PodcastController::class, 'show']);

    //Evaluation Routes
    Route::get('evaluations', [EvaluationController::class, 'index']);
    Route::get('evaluation/{id}', [EvaluationController::class, 'show']);


    //Question Routes
    Route::get('questions', [QuestionController::class, 'index']);
    Route::get('question/{id}', [QuestionController::class, 'show']);


    //subscription Plans Routes
    Route::get('subscription-plans', [SubscriptionPlanController::class, 'index']);
    Route::get('subscription-plan/{id}', [SubscriptionPlanController::class, 'show']);

    //video Routes
    Route::get('videos', [VideoController::class, 'index']);
    Route::get('video/{id}', [VideoController::class, 'show']);
    
    Route::get('video/stream/{id}', function ($id, Request $request){
        $video = Video::find($id);
        return VideoStream::stream($video, $request);
    });

    Route::post('/video/{videoId}/update-progress', [VideoController::class, 'updateProgress']);
    Route::get('/video/{videoId}/progress', [VideoController::class, 'getVideoProgress']);

//    Evaluation Answer Get
    Route::post('evaluations/answer', [EvaluationController::class, 'getAnswer']);
    Route::get('evaluations/result/{id}', [EvaluationController::class, 'evaluationResultShow']);

    Route::post('/user-ai-tag/store', [TagController::class, 'aiTagStore']);

    Route::group(['prefix' => 'ratings'], function(){
        Route::get('/', [RatingsController::class, 'index'])->name('ratings.index');
        Route::post('/store', [RatingsController::class, 'store'])->name('ratings.store');
        Route::delete('/delete/{id}', [RatingsController::class, 'delete'])->name('ratings.delete');
    });

});



// Firebase Token Route
Route::post("firebase/token/add", [FirebaseTokenController::class, "store"]);
Route::post("firebase/token/get", [FirebaseTokenController::class, "getToken"]);
Route::post("firebase/token/detele", [FirebaseTokenController::class, "deleteToken"]);
// Route for RevenueCat Webhook
Route::post('/revenuecat/webhook', [RevenueCatController::class, 'webhook']);