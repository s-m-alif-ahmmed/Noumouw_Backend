<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class DynamicPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DynamicPage::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    $status = '<label class="inline-flex items-center cursor-pointer">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" value="" class="sr-only peer" id="customSwitch' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= ' checked';
                    }
                    $status .= '>';
                    $status .= '<div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>';
                    $status .= '</label>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="" role="group" aria-label="Basic example" style="gap: 10px;display: flex;">
                          <a class="flex items-center justify-center w-8 h-8 transition-all duration-200 ease-linear rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500" href="' . route('dynamic-page.edit', $data->id) . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="pencil" class="lucide lucide-pencil w-4 h-4"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg></a>

                        <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" data-modal-target="deleteModal" class="remove-item-btn flex items-center justify-center w-8 h-8 transition-all duration-200 ease-linear rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:bg-custom-500/20 dark:hover:text-custom-500"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="trash-2" class="lucide lucide-trash-2 w-4 h-4"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg></a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.layout.setting.dynamic_page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layout.setting.dynamic_page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'page_title' => 'required|string|max:100',
            'page_content' => 'required|string',
        ]);

        $validatedData['page_slug'] = Str::slug($request->page_title);
        $validatedData['status'] = 'active';
        //dd($validatedData);
        DynamicPage::create($validatedData);
        flash()->success('Dynamic Page Created Successfully');
        return redirect()->route('dynamic-page.index');
    }

    /**
     * Display the specified resource.
     */
    public function showDaynamicPage($page_slug)
    {
        $page = DynamicPage::where('page_slug', $page_slug)->where('status', 'active')->firstOrFail();
        $pages = DynamicPage::where('status', 'active')->get();

        return view('fontend.dynamic_page.dynamic-page-show',compact(['page','pages']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = DynamicPage::findOrFail($id);
        return view("backend.layout.setting.dynamic_page.edit", compact("data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'page_title' => 'required|string|max:100',
            'page_content' => 'required|string',
        ]);
        $data = DynamicPage::findOrFail($id);
        $validatedData['page_slug'] = Str::slug($request->page_title);
        $data->update($validatedData);
        flash()->success('Dynamic Page Updated Successfully');
        return redirect()->route('dynamic-page.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = DynamicPage::findOrFail($id);
        if(empty($data)){
            return response()->json([
                "success"=> false,
                "message"=> "Item not found."
               ],404);
        }

        if(!empty($data->image)){
            Helper::fileDelete(public_path($data->image));
        }
        if(!empty($data->background_image)){
            Helper::fileDelete(public_path($data->background_image));
        }

        $data->delete();

        return response()->json([
            "success"=> true,
            "message"=> "Item deleted successfully."
        ]);
    }


    public function status(Request $request, $id)
    {
        // Find the blog by ID or return 404 if not found
        $data = DynamicPage::find($id);
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
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
            'message' => 'Item status changed successfully.'
        ]);
    }
}
