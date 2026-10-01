<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Web\Backend\TagController;
use App\Http\Controllers\NotificationSendController;
use App\Http\Controllers\Web\Backend\VideoController;
use App\Http\Controllers\Web\Backend\CourseController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\PodcastController;
use App\Http\Controllers\Web\Backend\ActivityController;
use App\Http\Controllers\Web\Backend\GetStartController;
use App\Http\Controllers\Web\Backend\QuestionController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\EvaluationController;
use App\Http\Controllers\Web\Backend\InstructorController;
use App\Http\Controllers\Web\Backend\NotificationController;
use App\Http\Controllers\Web\Backend\SubscriptionController;
use App\Http\Controllers\Web\Backend\PushNotificationController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\DynamicPageController;
use App\Http\Controllers\Web\Backend\Settings\PrivacyPolicyController;
use App\Http\Controllers\Web\Backend\Settings\SystemSettingController;
use App\Http\Controllers\Web\Backend\UserController;
use App\Http\Controllers\Web\Backend\RatingsController;
use Symfony\Component\HttpFoundation\StreamedResponse;


//Firebase home routes
Route::get('/home', [NotificationSendController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'],function(){
    Route::post('/store-token', [NotificationSendController::class, 'updateDeviceToken'])->name('store.token');
    Route::post('/send-web-notification', [NotificationSendController::class, 'sendNotification'])->name('send.web-notification');
});

Route::middleware(['web', 'auth','admin'])->group(function () {

    //    Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class,'index'])->name('dashboard');

    //    User
    Route::get('users', [UserController::class, 'index'])->name('user.index');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('users/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::get('users/{id}',[UserController::class,'show'])->name('user.show');
    Route::get('/user/show/children/{id}', [UserController::class, 'userChildren'])->name('user.show.children');
    Route::post('users/status/{id}', [UserController::class, 'status'])->name('user.status');
    Route::delete('users/delete/{id}',[UserController::class,'destroy'])->name('user.destroy');


    //  settings routes
    Route::controller(SystemSettingController::class)->name('setting.')->group(function () {
        Route::get('setting-system','index')->name('system.index');
        Route::post('setting-update', 'update')->name('system.update');
        Route::get('setting-configuration', 'configuration')->name('configuration.index');
        // Mail Settings
        Route::post('setting-configuration-mail', 'mailSettingUpdate')->name('configuration.mail');
        // Payments Settings
        Route::post('setting-configuration-payment',  'stripeSettingUpdate')->name('configuration.payment');
        // Social App Settings
        Route::post('setting-configuration-social', 'socialAppUpdate')->name('configuration.social');
    });

    //  Dynamic pages routes
    Route::resource('/dynamic-page', DynamicPageController::class);
    Route::post('/dynamic-page/status/{id}', [DynamicPageController::class, 'status'])->name('dynamic.page.status');

    //  admin profile routes
    Route::controller(ProfileController::class)->name('setting.')->group(function () {
        Route::get('setting-profile', 'index')->name('profile.index');
        Route::post('setting-profile', 'update')->name('profile.update');
        Route::post('setting-profile-password', 'UpdatePassword')->name('profile.password');
        Route::post('setting-profile-picture','UpdateProfilePicture')->name('profile.picture');
    });

    //  notification routes
    Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification'])->name('notifications.delete');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    //! push Notification

    Route::get('push-notifications', [NotificationSendController::class, 'index'])->name('push-notification.index');
    Route::post('send-push-notification', [NotificationSendController::class, 'sendNotification'])->name('send.notification');

    // Get Start Message Route
    Route::resource('get-start', GetStartController::class)->except(['show']);
    Route::post('get-start/status/{id}', [GetStartController::class, 'status'])->name('get-start.status');

    //! Privacy Policy routes
    Route::resource('/privacy-policy', PrivacyPolicyController::class);
    Route::post('/privacy-policy/status/{id}', [PrivacyPolicyController::class, 'status'])->name('privacy.policy.status');

    //! Category Routes
    Route::resource('/category', CategoryController::class);
    Route::post('/categories/status/{id}', [CategoryController::class, 'status'])->name('category.status');

    //! Tag Routes
    Route::resource('/tag', TagController::class);

    //! Support  routes
    Route::get('/support',[SupportController::class,'index'])->name('support.index');
    Route::post('/support/send',[SupportController::class,'send'])->name('support.send');
    Route::get('/support/tickets',[SupportController::class,'tickets'])->name('support.tickets');
    Route::get('/support/tickets/show/{id}',[SupportController::class,'show'])->name('support.tickets.show');
    Route::post('/support/tickets/status/{id}',[SupportController::class,'status'])->name('support.tickets.status');
    Route::delete('/support/tickets/destroy/{id}',[SupportController::class,'destroy'])->name('support.tickets.destroy');

    //! Ratings Routes
    Route::get('ratings', [RatingsController::class, 'index'])->name('ratings.index');
    Route::post('ratings/status/{id}', [RatingsController::class, 'status'])->name('ratings.status');
    Route::delete('ratings/delete/{id}', [RatingsController::class, 'destroy'])->name('ratings.destroy');
});

Route::middleware(['web', 'auth','admin'])->group(function () {
//! Instructor Routes
   Route::get('instructors',[InstructorController::class,'index'])->name('instructor.index');
   Route::post('instructor/store',[InstructorController::class,'store'])->name('instructor.store');
   Route::get('instructor/edit/{id}',[InstructorController::class,'edit'])->name('instructor.edit');
   Route::post('instructor/update/{id}',[InstructorController::class,'update'])->name('instructor.update');
   Route::delete('instructor/delete/{id}',[InstructorController::class,'destroy'])->name('instructor.destroy');

//! Course Routes
    Route::get('course',[CourseController::class,'index'])->name('course.index');
    Route::get('course/create',[CourseController::class,'create'])->name('course.create');
    Route::post('course/store',[CourseController::class,'store'])->name('course.store');
    Route::get('course/edit/{id}',[CourseController::class,'edit'])->name('course.edit');
    Route::put('course/update/{id}',[CourseController::class,'update'])->name('course.update');
    Route::delete('course/delete/{id}',[CourseController::class,'destroy'])->name('course.destroy');
    Route::post('/course/status/{id}', [CourseController::class, 'status'])->name('course.status');
    Route::get('/course/view/{id}', [CourseController::class, 'view'])->name('course.show');
    Route::delete('/course/content/delete/{id}',[CourseController::class,'deleteCourseContent'])->name('course.content.destroy');
    Route::get('/course/content/details/{id}', [CourseController::class, 'getContentDetails'])->name('course.content.details');
    Route::post('/contents/update-order', [CourseController::class, 'updateOrder'])->name('contents.updateOrder');

    Route::get('course_new',[CourseController::class,'index_new'])->name('course.index_new');
    Route::get('course/create_new',[CourseController::class,'create_new'])->name('course.create_new');
    Route::post('course/store_new',[CourseController::class,'store_new'])->name('course.store_new');
    Route::get('course/edit_new/{id}',[CourseController::class,'edit_new'])->name('course.edit_new');
    Route::put('course/update_new/{id}',[CourseController::class,'update_new'])->name('course.update_new');

//! Video Routes
    Route::get('video',[VideoController::class,'index'])->name('video.index');
    Route::get('video/create',[VideoController::class,'create'])->name('video.create');
    Route::post('video/store',[VideoController::class,'store'])->name('video.store');
    Route::post('video/ajax/store',[VideoController::class, 'ajaxStore'])->name('video.ajax.store');
    Route::post('video/chunk-upload', [VideoController::class, 'chunkUpload'])->name('video.chunkUpload');
    Route::get('video/edit/{id}',[VideoController::class,'edit'])->name('video.edit');
    Route::get('video/{id}/edit-data', [VideoController::class, 'editData'])->name('video.editData');
    Route::put('video/update/{id}',[VideoController::class,'update'])->name('video.update');
    Route::delete('video/delete/{id}',[VideoController::class,'destroy'])->name('video.destroy');
    Route::post('/video/status/{id}', [VideoController::class, 'status'])->name('video.status');



//! Activity Routes
    Route::get('activity',[ActivityController::class,'index'])->name('activity.index');
    Route::get('activity/create',[ActivityController::class,'create'])->name('activity.create');
    Route::post('activity/store',[ActivityController::class,'store'])->name('activity.store');
    Route::get('activity/edit/{id}',[ActivityController::class,'edit'])->name('activity.edit');
    Route::get('activity/{id}/edit-data',[ActivityController::class,'editData'])->name('activity.editData');
    Route::put('activity/update/{id}',[ActivityController::class,'update'])->name('activity.update');
    Route::delete('activity/delete/{id}',[ActivityController::class,'destroy'])->name('activity.destroy');
    Route::post('/activity/status/{id}', [ActivityController::class, 'status'])->name('activity.status');


//! Podcast Routes
   Route::get('podcast',[PodcastController::class,'index'])->name('podcast.index');
   Route::get('podcast/create',[PodcastController::class,'create'])->name('podcast.create');
   Route::post('podcast/store',[PodcastController::class,'store'])->name('podcast.store');
   Route::get('podcast/edit/{id}',[PodcastController::class,'edit'])->name('podcast.edit');
   Route::get('podcast/{id}/edit-data', [PodcastController::class, 'editData'])->name('podcast.editData');
   Route::put('podcast/update/{id}',[PodcastController::class,'update'])->name('podcast.update');
   Route::delete('podcast/delete/{id}',[PodcastController::class,'destroy'])->name('podcast.destroy');
   Route::post('/podcast/status/{id}', [PodcastController::class, 'status'])->name('podcast.status');



//! Question Routes
    Route::get('question',[QuestionController::class,'index'])->name('question.index');
    Route::post('question/store',[QuestionController::class,'store'])->name('question.store');
    Route::get('question/edit/{id}',[QuestionController::class,'edit'])->name('question.edit');
    Route::post('question/update/{id}',[QuestionController::class,'update'])->name('question.update');
    Route::delete('question/delete/{id}',[QuestionController::class,'destroy'])->name('question.destroy');
    Route::post('/question/status/{id}', [QuestionController::class, 'status'])->name('question.status');


//! Evaluation Routes
    Route::get('evaluation',[EvaluationController::class,'index'])->name('evaluation.index');
    Route::get('evaluation/create',[EvaluationController::class,'create'])->name('evaluation.create');
    Route::post('evaluation/store',[EvaluationController::class,'store'])->name('evaluation.store');
    Route::get('evaluation/edit/{id}',[EvaluationController::class,'edit'])->name('evaluation.edit');
    Route::get('evaluation/{id}/edit-data',[EvaluationController::class,'editData'])->name('evaluation.editData');
    Route::put('evaluation/update/{id}',[EvaluationController::class,'update'])->name('evaluation.update');
    Route::delete('evaluation/delete/{id}',[EvaluationController::class,'destroy'])->name('evaluation.destroy');
    Route::post('/evaluation/status/{id}', [EvaluationController::class, 'status'])->name('evaluation.status');
    Route::post('/evaluation/question',[EvaluationController::class,'evaluationQuestion'])->name('evaluation.question');


//! subscription Routes
    Route::get('subscription',[SubscriptionController::class,'index'])->name('subscription.index');
    Route::get('subscription/create',[SubscriptionController::class,'create'])->name('subscription.create');
    Route::post('subscription/store',[SubscriptionController::class,'store'])->name('subscription.store');
    Route::get('subscription/edit/{id}',[SubscriptionController::class,'show'])->name('subscription.edit');
    Route::put('subscription/update/{id}',[SubscriptionController::class,'update'])->name('subscription.update');
    Route::delete('subscription/delete/{id}',[SubscriptionController::class,'destroy'])->name('subscription.destroy');

});
//Route::get('/private-video', function (Request $request) {
//    if (!Auth::check()) {
//        return abort(403);
//    }
//
//    $path = $request->query('path');
//    if (!Storage::disk('private')->exists($path)) {
//        abort(404, 'File not found.');
//    }
//    return Storage::disk('private')->response($path);
//})->name('private.video');

//Route::get('/private-video/{path}', function ($path) {
//    // Ensure the user is authenticated
//    if (!auth()->check()) {
//        abort(403, 'Unauthorized access.');
//    }
//
//    // Decode URL if necessary
//    $path = urldecode($path);
//
//    // Check if the file exists in the private storage
//    if (!Storage::disk('private')->exists($path)) {
//        abort(404, 'File not found.');
//    }
//    // Serve the file securely
//    return Storage::disk('private')->response($path);
//})->where('path', '.*')->name('private.video');


Route::get('/private-video/{path}', function ($path) {
    // Ensure the user is authenticated
    if (!auth('sanctum')->check() && !auth()->check()) {
        abort(403, 'Unauthorized access.');
    }

    // Decode URL if necessary
    $path = urldecode($path);

    // Check if the file exists in the private storage
    if (!Storage::disk('private')->exists($path)) {
        abort(404, 'File not found.');
    }

    $file = Storage::disk('private')->path($path);
    $fileSize = filesize($file);
    $mimeType = Storage::disk('private')->mimeType($path);

    $headers = [
        'Content-Type' => $mimeType,
        'Content-Length' => $fileSize,
        'Accept-Ranges' => 'bytes',
    ];

    if (request()->header('Range')) {
        // Parse Range header
        $range = request()->header('Range');
        [$unit, $rangeSet] = explode('=', $range, 2);
        [$start, $end] = explode('-', $rangeSet, 2);

        $start = (int)$start;
        $end = $end === '' ? $fileSize - 1 : (int)$end;

        $length = $end - $start + 1;
        $headers['Content-Range'] = "bytes $start-$end/$fileSize";
        $headers['Content-Length'] = $length;

        return response()->stream(function () use ($file, $start, $length) {
            $stream = fopen($file, 'rb');
            fseek($stream, $start);
            echo fread($stream, $length);
            fclose($stream);
        }, 206, $headers);
    }

    return response()->stream(function () use ($file) {
        readfile($file);
    }, 200, $headers);
})->where('path', '.*')->name('private.video');

