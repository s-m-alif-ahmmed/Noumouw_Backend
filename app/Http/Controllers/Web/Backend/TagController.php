<?php
namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Tag::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($data) {
                    return '<div role="group" style="gap: 10px;display: flex; justify-content: center; text-align: center">
                        <button class="edit flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" data-id="' . $data->id . '" title="Edit Tag">
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

        return view('backend.layout.tag.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
        ]);
        try {
            Tag::create([
                'title' => $request->title,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tag created successfully!',
            ]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function edit(string $id)
    {
        $data = Tag::where('id', $id)->first();

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
            'title' => 'required|string|max:100',
        ]);
        try {
            $data = Tag::find($id);
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tag not found.',
                ], 404);
            }
            $data->update([
                'title' => $request->title
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Tag updated successfully!',
            ]);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {

        $data = Tag::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Tag not found.',
            ], 404);
        }
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tag deleted successfully!',
        ]);
    }
}
