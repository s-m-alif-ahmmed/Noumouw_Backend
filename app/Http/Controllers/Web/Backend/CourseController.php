<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Activity;
use App\Models\Evaluation;
use App\Models\Instructor;
use App\Models\Podcast;
use App\Models\Tag;
use App\Models\Course;
use App\Helpers\Helper;
use App\Models\Content;
use App\Models\CourseTag;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Course::with(['tags', 'subscription'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function ($data) {
                    $defaultImage = asset('backend/no-image.jpg');
                    $url = asset($data->thumbnail) ?? $defaultImage;
                    return '<img src="' . $url . '" alt="thumbnail" width="50px" height="50px" style="margin-left:20px;">';
                })
                ->addColumn('subscription_plans_id', function ($data) {
                    return $data->subscription ? $data->subscription->name : 'N/A';
                })
                ->addColumn('tags_data', function ($data) {
                    return $data->tags->pluck('title')->implode(', ');
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
                    return '<div role="group" style="gap: 10px;display: flex;">
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="' . route('course.show', $data->id) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </a>
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="' . route('course.edit', $data->id) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                        </a>
                        <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </a>
                    </div>';
                })
                ->rawColumns(['thumbnail', 'tags_data', 'status', 'action', 'subscription_plans_id'])
                ->make(true);
        }

        $tags = Tag::latest()->get();
        $subscription = SubscriptionPlan::latest()->get();
        return view('backend.layout.course.index', compact('tags', 'subscription'));
    }

    public function create()
    {
        $data = Tag::latest()->get();
        $subscription = SubscriptionPlan::latest()->get();
        return view('backend.layout.course.create', compact('data', 'subscription'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            //           'description' => 'nullable|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags.*' => 'required|exists:tags,id',
            'subscription_plans_id' => 'required|integer',
        ]);
        try {
            if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                $thumbnail_path = Helper::fileUpload($request->file('thumbnail'), 'course', getFileName($request->file('thumbnail')));
            } else {
                $thumbnail_path = 'uploads/course/default.png';
            }
            $course = Course::create([
                'name' => $request->name,
                'description' => $request->description,
                'thumbnail' => $thumbnail_path,
                'subscription_plans_id' => $request->subscription_plans_id,
            ]);
            $course->tags()->attach($request->tags);

            flash()->success('Course created successfully');
            return response()->json([
                'success' => true,
                'message' => 'Course created successfully'
            ]);
        } catch (\Exception $e) {
            flash()->error('Something went wrong');
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }


    public function edit($id)
    {
        try {
            $data = Course::findOrFail($id);
            $selectedTagIds = CourseTag::where('course_id', $id)->pluck('tag_id')->toArray();
            $allTags = Tag::all();
            $subscription = SubscriptionPlan::latest()->get();

            return view('backend.layout.course.edit', compact('data', 'allTags', 'selectedTagIds', 'subscription'));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            //            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags.*' => 'exists:tags,id',
            'subscription_plans_id' => 'required|integer',
        ]);

        try {
            $course = Course::findOrFail($id);

            if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                Helper::fileDelete(public_path($course->thumbnail)); // Delete old file
                $thumbnail_path = Helper::fileUpload($request->file('thumbnail'), 'course', getFileName($request->file('thumbnail')));
            } else {
                $thumbnail_path = $course->thumbnail;
            }

            $course->update([
                'name' => $request->name,
                'description' => $request->description,
                'thumbnail' => $thumbnail_path,
                'subscription_plans_id' => $request->subscription_plans_id,
            ]);

            $course->tags()->sync($request->tags);

            flash()->success('Course Updated Successfully');
            return redirect()->route('course.index');
        } catch (\Exception $exception) {
            flash()->error($exception->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Course::findOrFail($id);
            Helper::fileDelete(public_path($data->thumbnail));
            $data->delete();
            return response()->json(['success' => true, 'message' => 'Course deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function status($id)
    {

        $course = Course::findOrFail($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
        if ($course->status == 'active') {
            $course->status = 'inactive';
        } else {
            $course->status = 'active';
        }
        $course->save();

        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.',
        ], 200);
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $item) {
            Content::where('id', $item['id'])->update(['order' => $item['position']]);
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
    }


//    public function view(Request $request, $id)
//    {
//        $instructors = Instructor::select('id', 'name')->latest()->get();
//        $courses = Course::latest()->get();
//        $tags = Tag::latest()->get();
//
//        if ($request->ajax()) {
//            $data = Content::with(['contentable', 'course'])
//                ->where('course_id', $id)
//                ->orderBy('order', 'asc')
//                ->get();
//
//            return DataTables::of($data)
//                ->addIndexColumn()
//                ->addColumn('title', fn($data) => e($data->contentable?->title ?? 'N/A'))
//                ->addColumn('description', fn($data) => e($data->contentable?->description ?? 'N/A'))
//                ->addColumn('audio', function ($data) {
//                    if ($data->contentable?->file) {
//                        $url = asset($data->contentable->file);
//                        return '<audio controls><source src="' . e($url) . '" type="audio/mp3">Your browser does not support the audio tag.</audio>';
//                    }
//                    return 'N/A';
//                })
//                ->addColumn('video', function ($data) {
//                    if ($data->contentable?->file) {
//                        $url = asset($data->contentable->file);
//                        return '<video controls width="200"><source src="' . e($url) . '" type="video/mp4">Your browser does not support the video tag.</video>';
//                    }
//                    return 'N/A';
//                })
//                ->addColumn('action', function ($data) {
//                    $type = strtolower(class_basename($data->contentable_type));
//
//                    $editButton = '';
//
//                    switch ($type) {
//                        case 'video':
//                        case 'podcast':
//                            $editButton = '
//            <button class="edit-btn btn btn-secondary btn-sm"
//                data-modal-open="edit-podcast-modal"
//                data-id="' . e($data->id) . '"
//                data-type="' . e($type) . '"
//            >
//                Edit
//            </button>';
//                            break;
//
//                        case 'evaluation':
//                            $editButton = '
//            <button class="edit-btn"
//                data-modal-open="edit-evaluation-modal"
//                data-id="' . e($data->id) . '"
//                data-type="evaluation"
//            >
//                Edit
//            </button>';
//                            break;
//
//                        case 'activity':
//                            $editButton = '
//            <button class="edit-btn"
//                data-modal-open="edit-activity-modal"
//                data-id="' . e($data->id) . '"
//                data-type="activity"
//            >
//                Edit
//            </button>';
//                            break;
//                    }
//
//
//
//                    return '
//                    <div role="group" style="gap: 10px; display: flex;">
//                        <!-- View -->
//                        <button data-modal-open="edit-question" class="edit flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" data-id="' . e($data->id) . '">
//                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
//                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
//                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
//                            </svg>
//                        </button>
//
//                        <!-- Edit -->
//                        ' . $editButton . '
//
//                        <!-- Delete -->
//                        <a href="javascript:void(0);" onclick="showDeleteConfirm(' . e($data->id) . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
//                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
//                                <path d="M3 6h18"></path>
//                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
//                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
//                                <line x1="10" x2="10" y1="11" y2="17"></line>
//                                <line x1="14" x2="14" y1="11" y2="17"></line>
//                            </svg>
//                        </a>
//                    </div>';
//                })
//                ->rawColumns(['title', 'description', 'audio', 'video', 'action'])
//                ->make(true);
//        }
//
//        $data = Course::findOrFail($id);
//        return view('backend.layout.course.view', compact('data', 'instructors', 'courses', 'tags'));
//    }



    public function view(Request $request, $id)
    {
        $instructors    = Instructor::select('id','name')->latest()->get();
        $courses        = Course::latest()->get();
        $tags           = Tag::latest()->get();
        if ($request->ajax()) {

            $data = Content::with(['contentable', 'course'])
                ->where('course_id', $id)
                ->orderBy('order', 'asc') // Order by order column
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', fn($data) => $data->contentable?->title ?? 'N/A')
                ->addColumn('description', fn($data) => $data->contentable?->description ?? 'N/A')
                ->addColumn('audio', function ($data) {
                    if ($data->contentable?->file) {
                        $url = asset($data->contentable->file);
                        return '<audio controls><source src="' . $url . '" type="audio/mp3">Your browser does not support the audio tag.</audio>';
                    }
                    return 'N/A';
                })
                ->addColumn('video', function ($data) {
                    if ($data->contentable?->file) {
                        $url = asset($data->contentable->file);
                        return '<video controls width="200"><source src="' . $url . '" type="video/mp4">Your browser does not support the video tag.</video>';
                    }
                    return 'N/A';
                })
                ->addColumn('action', function ($data) {

                    $editRoute = '#';
                    $modelType = class_basename($data->contentable_type);

                    switch ($modelType) {
                        case 'Video':
                            $editRoute = route('video.edit', $data->contentable_id);
                            break;
                        case 'Podcast':
                            $editRoute = route('podcast.edit', $data->contentable_id);
                            break;
                        case 'Evaluation':
                            $editRoute = route('evaluation.edit', $data->contentable_id);
                            break;
                        case 'Activity':
                            $editRoute = route('activity.edit', $data->contentable_id);
                            break;
                    }

                    return '<div role="group" style="gap: 10px; display: flex;">

                    <button data-modal-open="edit-question" class="edit flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" data-id="' . $data->id . '">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577
                        16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    </button>

                    <a href="javascript:void(0);" class="edit-video-btn flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-blue-500 hover:bg-blue-100 dark:bg-zink-600 dark:text-zink-200"
                        data-id="' . $data->contentable_id . '" data-type="' . $modelType . '" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="size-6" fill="none" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                            <path d="m15 5 4 4"></path>
                        </svg>
                    </a>

                    <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            <line x1="10" x2="10" y1="11" y2="17"></line>
                            <line x1="14" x2="14" y1="11" y2="17"></line>
                        </svg>
                    </a>
                </div>';
                })
                ->rawColumns(['title','description', 'audio', 'video', 'action'])
                ->make(true);
        }
        $data           = Course::findOrFail($id);
        return view('backend.layout.course.view', compact('data', 'instructors', 'courses', 'tags'));
    }

    public function deleteCourseContent($id)
    {
        try {
            $content = Content::findOrFail($id);

            $contentable = $content->contentable;

            // Handle file deletion based on content type
            if ($contentable instanceof Video || $contentable instanceof Podcast || $contentable instanceof Evaluation || $contentable instanceof Activity) {
                if ($contentable->file && Storage::disk('public')->exists($contentable->file)) {
                    Storage::disk('public')->delete($contentable->file);
                }
            }

            // Delete the related model
            if ($contentable) {
                $contentable->delete();
            }

            // Finally, delete the Content record
            $content->delete();

            return response()->json([
                'success' => true,
                'message' => 'Content deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting content. Please try again.',
            ], 500);
        }
    }


    public function getContentDetails($id)
    {
        $data = Content::with(['contentable', 'course'])->findOrFail($id);

        if ($data) {
            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $data->contentable->title ?? 'N/A',
                    'description' => $data->contentable->description ?? 'N/A',
                    'audio' => $data->contentable->audio ?? 'N/A',
                    'video' => $data->contentable->video ?? 'N/A',
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Content not found.'
            ]);
        }
    }
}
