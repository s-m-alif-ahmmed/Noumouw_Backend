<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\DataTables\UsersDataTable;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', fn($data) => $data->name)
                ->addColumn('email', fn($data) => $data->email)
                ->addColumn('birth_date', function ($data) {
                    return optional($data->profile)->birth_date
                        ? Carbon::parse($data->profile->birth_date)->format('d, M Y')
                        : 'N/A';
                })
                ->addColumn('parent_role', fn($data) => $data->profile->parent_role ?? 'N/A')
                ->addColumn('country', fn($data) => $data->profile->country ?? 'N/A')
                ->addColumn('children_count', fn($data) => $data->children->count() ?? 0)
                ->addColumn('role', fn($data) => ucfirst($data->role))
                ->addColumn('avatar', function ($data) {
                    $defaultImage = asset('backend/user.png');
                    $url = $data->avatar ? asset($data->avatar) : $defaultImage;
                    return '<img src="' . $url . '" alt="Image" width="50px" height="50px">';
                })
                ->addColumn('status', function ($data) {
                    $backgroundColor  = $data->status == "active" ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status == "active" ? '26px' : '2px';
                    $sliderStyles     = "position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: transform 0.3s ease; transform: translateX($sliderTranslateX);";

                    return '<div class="form-check form-switch" style="margin-left:40px; position: relative; width: 50px; height: 24px; background-color: ' . $backgroundColor . '; border-radius: 12px; transition: background-color 0.3s ease; cursor: pointer;">
                            <input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $data->id . '" name="status" style="position: absolute; width: 100%; height: 100%; opacity: 0; z-index: 2; cursor: pointer;">
                            <span style="' . $sliderStyles . '"></span>
                            <label for="customSwitch' . $data->id . '" class="form-check-label" style="margin-left: 10px;"></label>
                        </div>';
                })
                ->addColumn('action', function ($data) {
                    return '<div role="group" style="gap: 10px;display: flex;">
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="' . route('user.show', $data->id) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </a>
                        <a href="#!" onclick="showDeleteConfirm('.$data->id.')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </a>
                    </div>';
                })
                ->rawColumns(['avatar', 'status', 'action'])
                ->make();
        }

        return view('backend.layout.user.index');
    }

    public function show($id)
    {
        $data = User::find($id);
        if (!$data) {
            return redirect()->back()->with('t-error', 'User not found');
        }
        return view('backend.layout.user.view', compact('data'));
    }

    public function userChildren($id, Request $request)
    {
        if ($request->ajax()) {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $children = $user->children; // Assuming you have a relationship defined

            return DataTables::of($children)
                ->addIndexColumn()
                ->addColumn('name', fn($child) => $child->name ?? 'N/A')
                ->addColumn('birth_date', function ($child) {
                    return optional($child)->birth_date
                        ? \Carbon\Carbon::parse($child->birth_date)->format('d, M Y')
                        : 'N/A';
                })
                ->rawColumns(['name', 'birth_date'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function status($id)
    {

        $data = User::findOrFail($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }
        if ($data->status == 'active') {
            $data->status = 'inactive';
        } else {
            $data->status = 'active';
        }
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'User status changed successfully.',
        ], 200);
    }

    public function destroy(string $id)
    {

        $data = User::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!',
        ]);
    }

}
