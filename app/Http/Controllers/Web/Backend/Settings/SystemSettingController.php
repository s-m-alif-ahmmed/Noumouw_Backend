<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class SystemSettingController extends Controller
{
    /**
     * Display the system Settings page.
     *
     * @return View
     */
    public function index()
    {
        $setting = SystemSetting::latest('id')->first();
        //dd($setting);
        return view('backend.layout.setting.system', compact('setting'));
    }

   /**
 * Update system Settings.
 *
 * This method validates and updates the system Settings including title, system name, contact information, and optional files such as logo and favicon.
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function update(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'system_name' => 'required|string',
        'email' => 'required|string',
        'contact_number' => 'required|string',
        'company_open_hour' => 'required|string',
        'copyright_text' => 'required|string',
        'logo' => 'nullable|mimes:jpeg,jpg,png,ico,svg',
        'favicon' => 'nullable|mimes:jpeg,jpg,png,ico,svg',
        'address' => 'required|string',
        'description' => 'required|string',
    ]);

    $setting = SystemSetting::firstOrNew();
    $setting->title = $request->title;
    $setting->system_name = $request->system_name;
    $setting->email = $request->email;
    $setting->contact_number = $request->contact_number;
    $setting->company_open_hour = $request->company_open_hour;
    $setting->copyright_text = $request->copyright_text;
    $setting->address = $request->address;
    $setting->description = $request->description;

    if ($request->hasFile('logo')) {
        if($setting->logo){
            Helper::fileDelete(public_path($setting->logo));
        }
        $setting->logo = Helper::fileUpload($request->file('logo'), 'logos', 'logo');
    }
    if ($request->hasFile('favicon')) {
        if($setting->favicon){
            Helper::fileDelete(public_path($setting->favicon));
        }
        $setting->favicon = Helper::fileUpload($request->file('favicon'), 'favicons', 'favicon');
    }
    $setting->save();

    flash()->success("System Setting Updated Successfully");
    return back();
}

/**
 * Display configuration Settings page.
 *
 * This method checks if the authenticated user exists and then returns the view for the configuration Settings.
 * If the user is not found, it redirects back.
 *
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
public function configuration()
{
    return view('backend.layout.setting.configuration');
}

/**
 * Update mail Settings.
 *
 * This method sanitizes and validates input values for mail Settings and updates the .env file accordingly.
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function mailSettingUpdate(Request $request)
{

        // Sanitize input values
        $request->merge([
            'mail_mailer' => preg_replace('/\s+/', '', $request->mail_mailer),
            'mail_host' => preg_replace('/\s+/', '', $request->mail_host),
            'mail_port' => preg_replace('/\s+/', '', $request->mail_port),
            'mail_username' => preg_replace('/\s+/', '', $request->mail_username),
            'mail_password' => preg_replace('/\s+/', '', $request->mail_password),
            'mail_encryption' => preg_replace('/\s+/', '', $request->mail_encryption),
            'mail_from_address' => preg_replace('/\s+/', '', $request->mail_from_address),
        ]);

        $request->validate([
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|string',
        ]);

        $envContent = File::get(base_path('.env'));
        $lineBreak = "\n";
        $envContent = preg_replace([
            '/MAIL_MAILER=(.*)\s/',
            '/MAIL_HOST=(.*)\s/',
            '/MAIL_PORT=(.*)\s/',
            '/MAIL_USERNAME=(.*)\s/',
            '/MAIL_PASSWORD=(.*)\s/',
            '/MAIL_ENCRYPTION=(.*)\s/',
            '/MAIL_FROM_ADDRESS=(.*)\s/',
        ], [
            'MAIL_MAILER=' . $request->mail_mailer . $lineBreak,
            'MAIL_HOST=' . $request->mail_host . $lineBreak,
            'MAIL_PORT=' . $request->mail_port . $lineBreak,
            'MAIL_USERNAME=' . $request->mail_username . $lineBreak,
            'MAIL_PASSWORD=' . $request->mail_password . $lineBreak,
            'MAIL_ENCRYPTION=' . $request->mail_encryption . $lineBreak,
            'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $lineBreak,
        ], $envContent);

        if ($envContent !== null) {
            File::put(base_path('.env'), $envContent);
        }

        flash()->success("Mail Setting Update successfully.");
        return redirect()->back();
}

/**
 * Update Stripe Settings.
 *
 * This method sanitizes and validates input values for Stripe Settings and updates the .env file accordingly.
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function stripeSettingUpdate(Request $request)
{
    if (User::find(auth()->user()->id)) {
        $request->validate([
            'stripe_key' => 'required|string',
            'stripe_secret' => 'required|string',
            'stripe_webhook_secret' => 'required|string',
        ]);

        $envContent = File::get(base_path('.env'));
        $lineBreak = "\n";
        $stripeKey = '"' . trim($request->stripe_key) . '"';
        $stripeSecret = '"' . trim($request->stripe_secret) . '"';
        $stripeWebhookSecret = '"' . trim($request->stripe_webhook_secret) . '"';

        $envContent = preg_replace([
            '/STRIPE_SECRET_KEY=(.*)\s/',
            '/STRIPE_PUBLIC_KEY=(.*)\s/',
            '/STRIPE_WEBHOOK_SECRET=(.*)\s/',
        ], [
            'STRIPE_SECRET_KEY=' . $stripeKey . $lineBreak,
            'STRIPE_PUBLIC_KEY=' . $stripeSecret . $lineBreak,
            'STRIPE_WEBHOOK_SECRET=' . $stripeWebhookSecret . $lineBreak,
        ], $envContent);

        if ($envContent !== null) {
            File::put(base_path('.env'), $envContent);
        }

        flash()->success('Stripe Setting Update successfully.');
        return redirect()->back();
    }
    return redirect()->back();
}

/**
 * Update social app Settings.
 *
 * This method sanitizes and validates input values for social app Settings (Google, Facebook, Apple) and updates the .env file accordingly.
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function socialAppUpdate(Request $request)
{
    // Sanitize input values
    $request->merge([
        'google_client_id' => preg_replace('/\s+/', '', $request->google_client_id),
        'google_client_secret' => preg_replace('/\s+/', '', $request->google_client_secret),
        'google_redirect_uri' => preg_replace('/\s+/', '', $request->google_redirect_uri),
        'facebook_client_id' => preg_replace('/\s+/', '', $request->facebook_client_id),
        'facebook_client_secret' => preg_replace('/\s+/', '', $request->facebook_client_secret),
        'facebook_redirect_uri' => preg_replace('/\s+/', '', $request->facebook_redirect_uri),
        'apple_client_id' => preg_replace('/\s+/', '', $request->apple_client_id),
        'apple_client_secret' => preg_replace('/\s+/', '', $request->apple_client_secret),
        'apple_redirect_uri' => preg_replace('/\s+/', '', $request->apple_redirect_uri),
    ]);

        $request->validate([
            'google_client_id' => 'required|string',
            'google_client_secret' => 'required|string',
            'google_redirect_uri' => 'required|string',
            'facebook_client_id' => 'required|string',
            'facebook_client_secret' => 'required|string',
            'facebook_redirect_uri' => 'required|string',
            'apple_client_id' => 'required|string',
            'apple_client_secret' => 'required|string',
            'apple_redirect_uri' => 'required|string',
        ]);

        $envContent = File::get(base_path('.env'));
        $lineBreak = "\n";
        $envContent = preg_replace([
            '/GOOGLE_CLIENT_ID=(.*)\s/',
            '/GOOGLE_CLIENT_SECRET=(.*)\s/',
            '/GOOGLE_REDIRECT_URI=(.*)\s/',
            '/FACEBOOK_CLIENT_ID=(.*)\s/',
            '/FACEBOOK_CLIENT_SECRET=(.*)\s/',
            '/FACEBOOK_REDIRECT_URI=(.*)\s/',
            '/APPLE_CLIENT_ID=(.*)\s/',
            '/APPLE_CLIENT_SECRET=(.*)\s/',
            '/APPLE_REDIRECT_URI=(.*)\s/',
        ], [
            'GOOGLE_CLIENT_ID=' . $request->google_client_id . $lineBreak,
            'GOOGLE_CLIENT_SECRET=' . $request->google_client_secret . $lineBreak,
            'GOOGLE_REDIRECT_URI=' . $request->google_redirect_uri . $lineBreak,
            'FACEBOOK_CLIENT_ID=' . $request->facebook_client_id . $lineBreak,
            'FACEBOOK_CLIENT_SECRET=' . $request->facebook_client_secret . $lineBreak,
            'FACEBOOK_REDIRECT_URI=' . $request->facebook_redirect_uri . $lineBreak,
            'APPLE_CLIENT_ID=' . $request->apple_client_id . $lineBreak,
            'APPLE_CLIENT_SECRET=' . $request->apple_client_secret . $lineBreak,
            'APPLE_REDIRECT_URI=' . $request->apple_redirect_uri . $lineBreak,
        ], $envContent);

        if ($envContent !== null) {
            File::put(base_path('.env'), $envContent);
        }
        flash()->success('Social Setting Update successfully.');
        return redirect()->back();

}

}
