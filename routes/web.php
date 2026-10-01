<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/common.php';

use App\Models\DynamicPage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Route::get('/run-migrate', function () {
     try {
        // Run the migration command
        Artisan::call('migrate');
        
        // Optional: Get the output buffer text from the command
        $output = Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'Database migration completed successfully!',
            'output' => $output
        ], 200);

    } catch (\Exception $e) {
        // Return the failure details safely
        return response()->json([
            'success' => false,
            'message' => 'Migration failed!',
            'error' => $e->getMessage()
        ], 500);
    }
});
Route::get('/run-query', function () {
    DB::statement('ALTER TABLE questions MODIFY link TEXT NULL');
    return 'Database query completed successfully!';
});

Route::get('/upload', function () {
    return view('frontend.file_upload');
});

// Run Seeder Route
Route::get('/run-seed', function () {
    // Run the database seeding
    Artisan::call('db:seed');
    return 'Database seeding completed successfully!';
});

// Clear Config Cache Route
Route::get('/clear-config', function () {
    // Clear the config cache
    Artisan::call('config:clear');
    return 'Config cache cleared successfully!';
});

// Run Migrate Fresh Route
Route::get('/run-migrate-fresh', function () {
    // Run the database migration
    Artisan::call('migrate:fresh');
    return 'Database migration fresh successfully!';
});

Route::get('/privacy-policy', function () {
    $policy = DynamicPage::where('page_slug', 'privacy-policy')->first();
    return view('fontend.privacy', compact('policy'));
});

Route::get('/terms-conditions', function () {
    $terms = DynamicPage::where('page_slug', 'terms-conditions')->first();
    return view('fontend.terms', compact('terms'));
});

Route::get('/show/{table}', function ($table) {
    $data = DB::table($table)->get();
    return $data;
});