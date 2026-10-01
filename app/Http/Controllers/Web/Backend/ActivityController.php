<?php

namespace App\Http\Controllers\Web\Backend;


use App\Models\ActivityTag;
use App\Models\Content;
use App\Models\Course;
use App\Helpers\Helper;
use App\Models\Activity;
use App\Models\CourseTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class ActivityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Activity::with('content.course')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('course_name', function ($data) {
                    return $data->content?->course?->name ? $data->content?->course?->name : 'N/A';
                })
                ->addColumn('status', function ($data) {
                    $status = '<label class="inline-flex items-center cursor-pointer">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $data->id . ')" type="checkbox" value="" class="sr-only peer" id="customSwitch' . $data->id . '" name="status"';
                    if ($data->status == 'active') {
                        $status .= ' checked';
                    }
                    $status .= '>';
                    $status .= '<div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full
                 rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>';
                    $status .= '</label>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div role="group" style="gap: 10px;display: flex;">

                    <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="' . route('activity.edit', $data->id) . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                    </a>
                    <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                    </a>
                </div>';
                })
                ->rawColumns(['course_name', 'action', 'status'])
                ->make(true);
        }

        return view('backend.layout.activity.index');
    }


    public function create(Request $request)
    {
        $courses = Course::select('id', 'name', 'thumbnail')->latest()->get();
        $tags = Tag::latest()->get();
        $selectedCourse = null;
        if ($request->has('id')) {
            $selectedCourse = Course::find($request->id);
        }
        return view('backend.layout.activity.create', compact('courses', 'tags', 'selectedCourse'));
    }

    // Store a newly created resource
    public function store(Request $request)
    {

        $request->validate([
            'title'   => 'required|string|max:100',
            'images' => 'required|array|min:1',
            'images.*'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'  =>  'required|string',
            'course_id' => 'required|exists:courses,id',
            'tags.*' => 'required|exists:tags,id',
        ]);

        try {

            $imagePath = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $imagePath = Helper::fileUpload($image, 'activity', getFileName($image));
                        $imagePaths[] = $imagePath;
                    }
                }
            }

            // if (empty($imagePaths)) {
            //     $imagePath[] = 'uploads/activity/default.png';
            // }

            DB::beginTransaction();
            $course = Course::find($request->course_id);

            // Get the highest order value and increment by 1
            $maxOrder = $course->contents()->max('order') ?? 0;
            $newOrder = $maxOrder + 1;

            $activity = Activity::create([
                'title' => $request->title,
                'description' => $request->description,
                'images' =>$imagePaths,
            ]);


            $activity->tags()->attach($request->tags);

            $course->contents()->create([
                'order' => $newOrder,
                'type' => 'activity',
                'contentable_id' => $activity->id,
                'contentable_type' => Activity::class,
            ]);

            DB::commit();

            flash()->success('Activity created successfully');

            return response()->json([
                'success' => true,
                'message' => 'Activity created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(string $id)
    {
        $data = Activity::findOrFail($id);
        $selectedTagIds = ActivityTag::where('activity_id', $id)->pluck('tag_id')->toArray();
        $allTags = Tag::all();
        $data->images = !empty($data->images) ? json_decode($data->images, true) : [];
        $courses = Course::all();
        return view('backend.layout.activity.edit', compact('data', 'allTags', 'selectedTagIds', 'courses'));
    }

    public function editData($id)
    {
        $activity = Activity::with('tags', 'content.course')->find($id);

        if (!$activity) {
            return response()->json([
                'success' => false,
                'message' => 'Activity not found',
            ], 404);
        }

        $data = Content::where('contentable_id', $id)
            ->where('contentable_type', Activity::class)
            ->first();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'            => $activity->id,
                'title'         => $activity->title,
                'description'   => $activity->description,
                'status'        => $activity->status,
                'images'        => $activity->images, // will return full URLs from the accessor
                'course_id'     => $data->course_id ?? null,
                'tags'          => $activity->tags->pluck('id'),
            ]
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'   => 'required|string|max:100',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'  =>  'required|string',
            'tags.*' => 'exists:tags,id',
        ]);

        try {

            $activity = Activity::findOrFail($id);

            if ($activity->images) {
                $oldImages = is_array($activity->images) ? $activity->images : json_decode($activity->images, true);
            } else {
                $oldImages = [];
            }

            // Handle removed images
            $removedImages = $request->input('removed_images', []);
            if (!empty($removedImages)) {
                foreach ($removedImages as $removedImage) {
                    // Remove from list
                    if (($key = array_search($removedImage, $oldImages)) !== false) {
                        unset($oldImages[$key]);
                        // Delete file
                        if (file_exists(public_path($removedImage))) {
                            unlink(public_path($removedImage));
                        }
                    }
                }
                $oldImages = array_values($oldImages); // Reset keys
            }

            $imagePaths = $oldImages;

            // Handle new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $imagePath = Helper::fileUpload($image, 'activity', getFileName($image));
                        $imagePaths[] = $imagePath;
                    }
                }
            }

            // if (empty($imagePaths)) {
            //     $imagePaths[] = 'uploads/activity/default.png';
            // }

            DB::beginTransaction();

            $course = Course::find($request->course_id);

            $activity->update([
                'title' => $request->title,
                'description' => $request->description,
                'images' => $imagePaths
            ]);

            $activity->tags()->sync($request->tags);

            // Update related content, if exists
            if ($request->course_id) {
                if ($activity->content) {
                    // Update the existing content record
                    $activity->content->update([
                        'course_id' => $request->course_id,
                    ]);
                } else {
                    // Get the highest order value and increment by 1
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    // Create a new content record
                    $activity->content()->create([
                        'order' => $newOrder,
                        'course_id'       => $request->course_id,
                        'type'            => 'activity', // Set the appropriate type
                        'contentable_id'  => $activity->id,
                        'contentable_type' => Activity::class,
                    ]);
                }
            }

            DB::commit();
            flash()->success('Activity Updated successfully');

            return response()->json([
                'success' => true,
                'message' => 'Activity Updated successfully'
            ]);


        } catch (\Exception $e) {
            DB::rollBack();

            flash()->error('Somethings went wrong');
            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {
        try {
            $data = Activity::findOrFail($id);

            if ($data->images) {
                $images = json_decode($data->images, true);
                foreach ($images as $imagePath) {
                    if (file_exists(public_path($imagePath))) {
                        unlink(public_path($imagePath));
                    }
                }
            }

            $data->delete();
            $data->content()->delete();


            return response()->json([
                'success' => true,
                'message' => 'Activity deleted successfully.',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    public function status($id)
    {

        $activity = Activity::findOrFail($id);

        if (! $activity) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
        if ($activity->status == 'active') {
            $activity->status = 'inactive';
        } else {
            $activity->status = 'active';
        }
        $activity->save();

        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.',
        ], 200);
    }
}
