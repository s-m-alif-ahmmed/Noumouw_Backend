<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\Profile;
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
            $data = User::where('role', 'user')->latest()->get();

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
                // ->addColumn('role', fn($data) => ucfirst($data->role))
                ->addColumn('avatar', function ($data) {
                    $defaultImage = asset('backend/user.png');
                    $url = $data->avatar ? asset($data->avatar) : $defaultImage;
                    return '<img src="' . $url . '" alt="Image" class="w-10 h-10 rounded-full object-cover shadow-sm border border-slate-100 mx-auto">';
                })
                ->addColumn('status', function ($data) {
                    $isChecked = $data->status == "active" ? 'checked' : '';
                    return '
                        <label class="relative inline-flex items-center cursor-pointer ml-4">
                            <input type="checkbox" class="sr-only peer" id="customSwitch' . $data->id . '" onchange="showStatusChangeAlert(' . $data->id . ')" ' . $isChecked . '>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        </label>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="flex justify-center gap-2">
                        <a href="' . route('user.show', $data->id) . '" class="flex items-center justify-center size-8 transition-all rounded-lg bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                        </a>
                        <a href="#!" onclick="showDeleteConfirm('.$data->id.')" class="flex items-center justify-center size-8 transition-all rounded-lg bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
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
        $data = User::with('profile')->find($id);
        if (!$data) {
            return redirect()->back()->with('t-error', 'User not found');
        }
        return view('backend.layout.user.view', compact('data'));
    }

    public function edit($id)
    {
        $data = User::with(['profile', 'children'])->find($id);
        if (!$data) {
            return redirect()->route('user.index')->with('t-error', 'User not found');
        }
        return view('backend.layout.user.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = User::with('profile')->find($id);
        if (!$data) {
            return redirect()->back()->with('t-error', 'User not found');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $data->id,
            'role' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
            'birth_date' => 'required|date',
            'parent_role' => 'nullable|in:father,mother',
            'country' => 'required|string|max:100',
            'is_parent' => 'nullable|boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'children' => 'nullable|array',
            'children.*.id' => 'nullable|integer|exists:childrens,id',
            'children.*.name' => 'nullable|string|max:255',
            'children.*.birth_date' => 'nullable|date',
            'children_to_delete' => 'nullable|array',
            'children_to_delete.*' => 'nullable|integer|exists:childrens,id',
        ]);

        $data->name = $request->name;
        $data->email = $request->email;
        $data->role = $request->role;
        $data->status = $request->status;

        if ($request->hasFile('avatar')) {
            if ($data->avatar && file_exists(public_path($data->avatar))) {
                Helper::fileDelete(public_path($data->avatar));
            }
            $avatarPath = Helper::fileUpload($request->file('avatar'), 'user/avatar', $request->file('avatar')->getClientOriginalName());
            if ($avatarPath) {
                $data->avatar = $avatarPath;
            }
        }

        $data->save();

        Profile::updateOrCreate(
            ['user_id' => $data->id],
            [
                'birth_date' => $request->birth_date ?: optional($data->profile)->birth_date,
                'parent_role' => $request->parent_role ?: optional($data->profile)->parent_role ?: 'father',
                'country' => $request->country ?: optional($data->profile)->country,
                'is_parent' => $request->has('is_parent') ? 1 : 0,
            ]
        );

        if ($request->filled('children_to_delete')) {
            $data->children()->whereIn('id', $request->children_to_delete)->delete();
        }

        foreach ($request->input('children', []) as $childData) {
            $name = trim($childData['name'] ?? '');
            $birthDate = $childData['birth_date'] ?? null;

            if ($name === '' && empty($birthDate)) {
                continue;
            }

            if (!empty($childData['id'])) {
                $child = $data->children()->find($childData['id']);
                if ($child) {
                    $child->update([
                        'name' => $name,
                        'birth_date' => $birthDate,
                    ]);
                    continue;
                }
            }

            $data->children()->create([
                'name' => $name,
                'birth_date' => $birthDate,
            ]);
        }

        flash()->success('User profile updated successfully.');
        return redirect()->route('user.show', $data->id);
    }

    public function userChildren($id, Request $request)
    {
        if ($request->ajax()) {
            $user = User::with('profile', 'children')->find($id);

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
