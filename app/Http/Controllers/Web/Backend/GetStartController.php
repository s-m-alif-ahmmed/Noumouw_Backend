<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\GetStart;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class GetStartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = GetStart::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('description', function ($data) {
                    return strlen($data->description) > 100 ? substr($data->description, 0, 100) . '...' : $data->description;
                })
                ->filterColumn('description', function($query, $keyword) {
                    $query->where('description', 'like', "%{$keyword}%");
                })
                ->addColumn('status', function ($data) {
                    $status = '<label class="inline-flex items-center cursor-pointer">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" value="" class="sr-only peer" id="customSwitch' . $data->id . '" name="status"';
                    if ($data->status == 'active') {
                        $status .= ' checked';
                    }
                    $status .= '>';
                    $status .= '<div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>';
                    $status .= '</label>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div role="group" style="gap: 10px;display: flex; text-alighn">
                        <button data-modal-open="edit-get-start" class="edit flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" data-id="' . $data->id . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                        </button>
                        <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </a>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layout.getstart.index');
    }

    // Store a newly created resource
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:250',
        ]);

        if(GetStart::count() >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'You can only add up to 3 items.',
            ], 400);    
        }

        try {
            GetStart::create([
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message created successfully!',
            ]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function edit(string $id)
    {
        $data = GetStart::where('id', $id)->first();

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
            'description' => 'required|string|max:500',
        ]);
        try {
            $data = GetStart::find($id);
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Message not found.',
                ], 404);
            }
            $data->update([
                'description' => $request->description
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully!',
            ]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {

        $data = GetStart::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully!',
        ]);
    }

    public function status(string $id)
    {
        $data = GetStart::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
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
            'message' => 'Item status changed successfully.',
        ]);
    }
}
