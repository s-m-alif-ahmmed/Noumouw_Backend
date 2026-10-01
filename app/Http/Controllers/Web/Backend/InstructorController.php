<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instructor;
use Yajra\DataTables\DataTables;
use App\Helpers\Helper;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Instructor::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('avatar_url', function ($data) {
                    return $data->avatar ? asset($data->avatar) : asset('backend/images/user.png');
                })
                ->addColumn('status', function ($data) {
                    return 'active'; // Default status for existing instructors
                })
                ->addColumn('action', function ($data) {
                    return '<div role="group" style="gap: 10px;display: flex; justify-content: center; text-align: center">
                        <button class="edit flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" data-id="' . $data->id . '" title="Edit Instructor">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                        </button>
                        <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('backend.layout.instructor.index');
    }

    // Store a newly created resource
    public function store(Request $request)
    {

        $request->validate([
            'name'          => 'required|string|max:100',
            'avatar'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio'           => 'required|string',
            'phone'         => 'required',
            'role'          => 'required',
            'designation'   => 'required',
            'country'       => 'required',
            'address'       => 'required',
            'email'         => 'required',
            'services'      => 'required',
        ], ['phone.phone'   => 'The Number should be Valid']);

        try {
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                $avatar_path = Helper::fileUpload($request->file('avatar'), 'instructor', getFileName($request->file('avatar')));
            } else {
                $avatar_path = 'uploads/instructor/default.png';
            }

            Instructor::create([
                'name' => $request->name,
                'avatar' => $avatar_path,
                'bio' => $request->bio,
                'phone' => $request->phone,
                'role' => $request->role,
                'designation' => $request->designation,
                'country' => $request->country,
                'address' => $request->address,
                'email' => $request->email,
                'services' => $request->services,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Instructor created successfully!',
            ]);
        } catch (\Exception $e) {

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit(string $id)
    {
        $data = Instructor::where('id', $id)->first();

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio'           => 'required|string',
            'phone'         => 'phone:US,BD',
            'role'          => 'required',
            'designation'   => 'required',
            'country'       => 'required',
            'address'       => 'required',
            'email'         => 'required',
            'services'      => 'required',
        ], ['phone.phone' => 'The Number should be Valid']);

        try {
            $data = Instructor::find($id);
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found.',
                ], 404);
            }

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                if ($data->avatar) {
                    Helper::fileDelete(public_path($data->avatar));
                }
                $avatar_path = Helper::fileUpload($request->file('avatar'), 'instructor', getFileName($request->file('avatar')));
            } else {
                $avatar_path = $data->avatar;
            }

            $data->update([
                'name' => $request->name,
                'avatar' => $avatar_path,
                'bio' => $request->bio,
                'phone' => $request->phone,
                'role' => $request->role,
                'designation' => $request->designation,
                'country' => $request->country,
                'address' => $request->address,
                'email' => $request->email,
                'services' => $request->services,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Instructor updated successfully!',
            ]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {

        $data = Instructor::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
        if ($data->avatar) {
            Helper::fileDelete(public_path($data->avatar));
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Instructor deleted successfully!',
        ]);
    }
}
