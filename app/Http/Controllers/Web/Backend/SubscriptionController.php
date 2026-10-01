<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\SubscriptionPlan;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubscriptionPlan::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '<div role="group" style="gap: 10px;display: flex; justify-content: center; text-align: center">
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="' . route('subscription.edit', $data->id) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                        </a>
                        <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layout.subscription.index');
    }

    public function create()
    {
        return view('backend.layout.subscription.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'revenue_cart_product_id' => 'required',
        ]);

        try {

            SubscriptionPlan::create([
                'name' => $request->name,
                'duration' => $request->duration,
                'price' => $request->price,
                'revenue_cart_product_id' => $request->revenue_cart_product_id,
            ]);

            return redirect()->route('subscription.index')->with('success', 'Subscription plan created successfully!');
            // flash()->success('Subscription plan created successfully');

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Subscription plan created successfully'
            // ]);

        } catch (\Exception $e) {
            // flash()->error($e->getMessage());
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Something went wrong: ' . $e->getMessage()
            // ]);
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = SubscriptionPlan::findOrFail($id);
        return view('backend.layout.subscription.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'revenue_cart_product_id' => 'required',
        ]);

        try {
            $data = SubscriptionPlan::findOrFail($id);
            $data->update([
                'name' => $request->name,
                'duration' => $request->duration,
                'price' => $request->price,
                'revenue_cart_product_id' => $request->revenue_cart_product_id,
            ]);
            return redirect()->route('subscription.index')->with('success', 'subscription plan updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('subscription.index')->with('error', $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $data = SubscriptionPlan::findOrFail($id);
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'subscription plan deleted successfully',
            ]);
        } catch (\Exception $e) {
          return response()->json([
            'success' => false,
            'message' => $e->getMessage()
            ]);
        }
    }
}
