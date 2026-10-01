<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\SupportContact;

use Yajra\DataTables\DataTables;

class SupportController extends Controller
{
    public function index()
    {
        return view('fontend.support.index');
    }

    public function send(Request $request)
    {

        $request->validate([
            'fullName' => 'required|string|max:50',
            'email' => 'required|email',
            'country' => 'required|string',
            'message' => 'required|string',
        ]);

        //  dd($request->all());

        if ($this->isOnline()) {

            $mail = [
                'recipient' => 'jalismahamud31@gmail.com',
                'fullName' => $request->fullName,
                'email' => $request->email,
                'country' => $request->country,
                'body' => $request->message,
            ];


            // Mail::to($mail)->send(new ContactMail($mail));

            Mail::send('email.support', $mail, function ($message) use ($mail) {
                $message->to($mail['recipient'])
                    ->from($mail['email'], $mail['fullName'])
                    ->subject('Support mail from ' . $mail['fullName']);
            });

            return redirect()->back()->with('success', 'Your message has been sent successfully');
        } else {
            return redirect()->back()->with('error', 'Please check your internet connection');
        }
    }

    public function isOnline($site = "http://google.com")
    {
        if (@fopen($site, "r")) {

            return true;
        } else {
            return false;
        }
    }

    public function tickets(Request $request)
    {
        if ($request->ajax()) {
            $data = SupportContact::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('message', function ($data) {
                    if (strlen($data->message) > 30) {
                        return substr($data->message, 0, 30) . '...';
                    }
                    return $data->message;
                })
                ->addColumn('status', function ($data) {
                    $pendingSelected = $data->status == 'pending' ? 'selected' : '';
                    $resolvedSelected = $data->status == 'resolved' ? 'selected' : '';
                    $closedSelected = $data->status == 'closed' ? 'selected' : '';

                    return '
                        <select class="form-select status-select px-3 py-1.5 text-xs font-bold rounded-lg border border-slate-200 bg-slate-50 text-slate-600 focus:ring focus:ring-blue-500/20 outline-none text-center" 
                                data-id="' . $data->id . '" 
                                onchange="changeStatus(this)">
                            <option value="pending" ' . $pendingSelected . '>Pending</option>
                            <option value="resolved" ' . $resolvedSelected . '>Resolved</option>
                            <option value="closed" ' . $closedSelected . '>Closed</option>
                        </select>
                    ';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="flex justify-center gap-2">
                        <a href="#!" onclick="viewDetails(' . $data->id . ')" class="flex items-center justify-center size-8 transition-all rounded-lg hover:bg-blue-600 hover:text-white shadow-sm">
                            <svg    xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </a>
                        <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center size-8 transition-all rounded-lg hover:bg-red-600 hover:text-white shadow-sm">
                             <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </a>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layout.support.index');
    }

    public function show($id)
    {
        try {
            $data = SupportContact::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ]);
        }
    }

    public function status(Request $request, $id)
    {
        try {
            $data = SupportContact::findOrFail($id);
            $data->status = $request->status;
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $data = SupportContact::findOrFail($id);
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }
}
