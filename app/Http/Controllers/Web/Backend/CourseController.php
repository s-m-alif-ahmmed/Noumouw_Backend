<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Category;
use App\Models\Activity;
use App\Models\Evaluation;
use App\Models\Instructor;
use App\Models\Podcast;
use App\Models\PodcastTag;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Course;
use App\Helpers\Helper;
use App\Models\Content;
use App\Models\CourseTag;
use App\Models\Video;
// use RahulHaque\Filepond\Facades\Filepond;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Model;
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
                    $tags = $data->tags->pluck('title');

                    $limitedTags = $tags->take(3)->implode(', ');

                    return $limitedTags . ($tags->count() > 3 ? '...' : '');
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
                    return '<div role="group" style="gap: 10px;display: flex; justify-content: center;">
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="' . route('course.show', $data->id) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="18" height="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </a>
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="' . route('course.edit', $data->id) . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                        </a>
                        <a href="#!" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
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

    public function index_new(Request $request)
    {
        if ($request->ajax()) {
            $data = Course::with(['tags', 'subscription', 'category'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail_url', function ($data) {
                    return $data->thumbnail ? asset($data->thumbnail) : asset('backend/no-image.jpg');
                })
                ->addColumn('category_name', function ($data) {
                    return $data->category ? $data->category->name : 'Uncategorized';
                })
                ->addColumn('subscription_plans_id', function ($data) {
                    return $data->subscription ? $data->subscription->name : 'N/A';
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at->format('M d, Y');
                })
                ->addColumn('tags_data', function ($data) {
                    $tags = $data->tags;
                    $html = '';
                    foreach ($tags->take(3) as $tag) {
                        $html .= '<span class="inline-block px-2 py-0.5 rounded text-[10px] bg-blue-100 text-blue-700 font-bold border border-blue-200 mr-1">' . $tag->title . '</span>';
                    }
                    if ($tags->count() > 3) $html .= '<span class="text-slate-400 text-[10px]">+' . ($tags->count() - 3) . '</span>';
                    return $html;
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->status == 'active' ? 'checked' : '';
                    return '
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" onclick="showStatusChangeAlert(' . $data->id . ')" class="sr-only peer" ' . $checked . '>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>';
                })
                ->addColumn('action', function ($data) {
                    return '
                    <div class="flex justify-center gap-2">
                        <a href="' . route('course.show', $data->id) . '" class="p-2 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="View Details">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="' . route('course.edit_new', $data->id) . '" class="p-2 bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit Course">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                        <button onclick="showDeleteConfirm(' . $data->id . ')" class="p-2 bg-slate-50 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete Course">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </button>
                    </div>';
                })
                ->rawColumns(['tags_data', 'status', 'action'])
                ->make(true);
        }

        $tags = Tag::latest()->get();
        $subscription = SubscriptionPlan::latest()->get();
        return view('backend.layout.course_new.index', compact('tags', 'subscription'));
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
                'category_id' => $request->category_id,
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
    public function store_new(Request $request)
    {
        // dd($request->all());
        $courseMessages = [
            'name.required' => 'Please enter the course name.',
            'name.string' => 'The course name must be valid text.',
            'name.max' => 'The course name may not be greater than 255 characters.',
            'thumbnail.required' => 'Please upload a course thumbnail.',
            'thumbnail.image' => 'The course thumbnail must be an image.',
            'thumbnail.mimes' => 'The course thumbnail must be a jpeg, png, jpg, gif, or svg file.',
            'thumbnail.max' => 'The course thumbnail may not be greater than 2 MB.',
            'tags.required' => 'Please select at least one course tag.',
            'tags.array' => 'Please select valid course tags.',
            'tags.min' => 'Please select at least one course tag.',
            'tags.*.required' => 'Please select at least one course tag.',
            'tags.*.exists' => 'One of the selected course tags is invalid.',
            'subscription_plans_id.required' => 'Please select a subscription plan.',
            'subscription_plans_id.integer' => 'Please select a valid subscription plan.',
            'category_id.required' => 'Please select a category.',
            'category_id.integer' => 'Please select a valid category.',
            'category_id.exists' => 'The selected category is invalid.',
            'description.string' => 'The course description must be valid text.',
        ];

        $contentMessages = [
            'contents.*.type.required' => 'Please select a content type for this block.',
            'contents.*.type.in' => 'Please select a valid content type.',
            'contents.*.video_title.required' => 'Please enter the video title.',
            'contents.*.video_title.string' => 'The video title must be valid text.',
            'contents.*.video_title.max' => 'The video title may not be greater than 100 characters.',
            'contents.*.video_url.required' => 'Please upload a video file.',
            'contents.*.video_url.string' => 'The uploaded video path is invalid. Please upload the video again.',
            'contents.*.video_image.image' => 'The video thumbnail must be an image.',
            'contents.*.video_image.max' => 'The video thumbnail may not be greater than 2 MB.',
            'contents.*.video_instructor_id.required' => 'Please select a video instructor.',
            'contents.*.video_instructor_id.exists' => 'The selected video instructor is invalid.',
            'contents.*.duration.string' => 'The video duration is invalid.',
            'contents.*.activity_title.required' => 'Please enter the activity title.',
            'contents.*.activity_title.string' => 'The activity title must be valid text.',
            'contents.*.activity_title.max' => 'The activity title may not be greater than 100 characters.',
            'contents.*.activity_description.required' => 'Please enter the activity description.',
            'contents.*.activity_description.string' => 'The activity description must be valid text.',
            'contents.*.activity_image.array' => 'Please upload valid activity images.',
            'contents.*.activity_image.min' => 'Please upload at least one activity image.',
            'contents.*.activity_image.*.image' => 'Each activity image must be an image file.',
            'contents.*.activity_image.*.mimes' => 'Each activity image must be a jpeg, png, jpg, gif, or svg file.',
            'contents.*.activity_image.*.max' => 'Each activity image may not be greater than 2 MB.',
            'contents.*.podcast_title.required' => 'Please enter the podcast title.',
            'contents.*.podcast_title.string' => 'The podcast title must be valid text.',
            'contents.*.podcast_title.max' => 'The podcast title may not be greater than 255 characters.',
            'contents.*.podcast_description.required' => 'Please enter the podcast description.',
            'contents.*.podcast_description.string' => 'The podcast description must be valid text.',
            'contents.*.podcast_file.required' => 'Please upload an audio file.',
            'contents.*.podcast_file.file' => 'The podcast audio must be a valid file.',
            'contents.*.podcast_file.mimes' => 'The podcast audio must be an mp3 file.',
            'contents.*.podcast_instructor_id.required' => 'Please select a podcast instructor.',
            'contents.*.podcast_instructor_id.exists' => 'The selected podcast instructor is invalid.',
            'contents.*.evaluation_title.required' => 'Please enter the evaluation title.',
            'contents.*.evaluation_title.string' => 'The evaluation title must be valid text.',
            'contents.*.evaluation_title.max' => 'The evaluation title may not be greater than 255 characters.',
            'contents.*.questions.required' => 'Please add at least one evaluation question.',
            'contents.*.questions.array' => 'Please add valid evaluation questions.',
            'contents.*.questions.min' => 'Please add at least one evaluation question.',
            'contents.*.questions.*.question.required' => 'Please enter the question text.',
            'contents.*.questions.*.question.string' => 'The question text must be valid text.',
            'contents.*.questions.*.answer.required' => 'Please select the correct answer.',
            'contents.*.questions.*.answer.boolean' => 'Please select a valid correct answer.',
            'contents.*.questions.*.url.string' => 'The question URL must be valid text.',
            'contents.*.questions.*.url.max' => 'The question URL may not be greater than 255 characters.',
            'contents.*.tags.required' => 'Please select at least one tag for this content block.',
            'contents.*.tags.array' => 'Please select valid tags for this content block.',
            'contents.*.tags.min' => 'Please select at least one tag for this content block.',
            'contents.*.tags.*.required' => 'Please select at least one tag for this content block.',
            'contents.*.tags.*.exists' => 'One of the selected content tags is invalid.',
        ];

        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'required|array|min:1',
            'tags.*' => 'required|exists:tags,id',
            'subscription_plans_id' => 'required|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string',
        ], $courseMessages);

        $contents = $request->all()['contents'] ?? [];

        foreach ($contents as $index => $content) {
            $request->validate([
                "contents.$index.type" => 'required|in:video,activity,podcast,evaluation',
            ], $contentMessages);

            if (!isset($content['type'])) {
                continue; // skip invalid content block
            }
            if ($content['type'] == 'video') {
                $request->validate([
                    "contents.$index.video_title" => 'required|string|max:100',
                    // "contents.$index.video_url" => 'required|file|mimes:mp4,ogg,webm',
                    "contents.$index.video_url" => 'required|string',
                    "contents.$index.video_image" => 'nullable|image|max:2048',
                    "contents.$index.video_instructor_id" => 'required|exists:instructors,id',
                    "contents.$index.duration" => 'nullable|string',
                    "contents.$index.tags" => 'required|array|min:1',
                    "contents.$index.tags.*" => 'required|exists:tags,id',
                ], $contentMessages);
            }
            if ($content['type'] == 'activity') {
                $request->validate([
                    "contents.$index.activity_title" => 'required|string|max:100',
                    "contents.$index.activity_description" => 'required|string',
                    "contents.$index.activity_image" => 'nullable|array|min:1',
                    "contents.$index.activity_image.*" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    "contents.$index.tags" => 'required|array|min:1',
                    "contents.$index.tags.*" => 'required|exists:tags,id'
                ], $contentMessages);
            }

            if ($content['type'] == 'podcast') {
                $request->validate([
                    "contents.$index.podcast_title" => 'required|string|max:255',
                    "contents.$index.podcast_description" => 'required|string',
                    "contents.$index.podcast_file" => 'required|file|mimes:audio/mpeg,mp3',
                    "contents.$index.podcast_instructor_id" => 'required|exists:instructors,id',
                    "contents.$index.tags" => 'required|array|min:1',
                    "contents.$index.tags.*" => 'required|exists:tags,id'
                ], $contentMessages);
            }

            if ($content['type'] == 'evaluation') {
                $request->validate([
                    "contents.$index.evaluation_title" => 'required|string|max:255',
                    "contents.$index.questions" => 'required|array|min:1',
                    "contents.$index.questions.*.question" => 'required|string',
                    "contents.$index.questions.*.answer" => 'required|boolean',
                    "contents.$index.questions.*.url" => 'nullable|string|max:255',
                    "contents.$index.tags" => 'required|array|min:1',
                    "contents.$index.tags.*" => 'required|exists:tags,id',
                ], $contentMessages);
            }
        }

        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            $thumbnail_path = Helper::fileUpload($request->file('thumbnail'), 'course', getFileName($request->file('thumbnail')));
        } else {
            $thumbnail_path = 'uploads/course/default.png';
        }

        //  DB::beginTransaction();

        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'thumbnail' => $thumbnail_path,
            'subscription_plans_id' => $request->subscription_plans_id,
            'category_id' => $request->category_id,
        ]);
        $course->tags()->attach($request->tags);

        if (!empty($contents)) {
            foreach ($contents as $content) {
                if (!isset($content['type'])) {
                    continue; // skip invalid content block
                }

                if ($content['type'] == 'video') {
                    // Image

                    if (isset($content['video_image']) && $content['video_image']->isValid()) {
                        $thumbnail_path = Helper::fileUpload($content['video_image'], 'video', getFileName($content['video_image']));
                    } else {
                        $thumbnail_path = 'uploads/video/default.png';
                    }
                    if (isset($content['video_url']) && !empty($content['video_url'])) {
                        $file_path = $content["video_url"];
                    } else {
                        $file_path = 'course/video/default.mp4';
                    }
                    // Get the highest order value and increment by 1
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    $video = Video::create([
                        'title' => $content['video_title'],
                        'file' => $file_path,
                        'image' => $thumbnail_path,
                        'duration' => $content['duration'],
                        'instructor_id' => $content['video_instructor_id'],
                    ]);
                    $video->tags()->attach($content['tags']);

                    $course->contents()->create([
                        'order' => $newOrder,
                        'type' => 'video',
                        'contentable_id' => $video->id,
                        'contentable_type' => Video::class,
                    ]);
                } else if ($content['type'] == 'activity') {

                    $imagePaths = [];

                    if (!empty($content['activity_image']) && is_array($content['activity_image'])) {
                        foreach ($content['activity_image'] as $image) {
                            if ($image->isValid()) {
                                $imagePaths[] = Helper::fileUpload(
                                    $image,
                                    'activity',
                                    getFileName($image)
                                );
                            }
                        }
                    }

                    // if (empty($imagePaths)) {
                    //     $imagePaths[] = 'uploads/activity/default.png';
                    // }
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    $activity = Activity::create([
                        'title' => $content['activity_title'],
                        'description' => $content['activity_description'],
                        'images' => $imagePaths,
                    ]);


                    $activity->tags()->attach($content['tags']);

                    $course->contents()->create([
                        'order' => $newOrder,
                        'type' => 'activity',
                        'contentable_id' => $activity->id,
                        'contentable_type' => Activity::class,
                    ]);
                } else if ($content['type'] == 'podcast') {
                    if (isset($content['podcast_file']) && $content['podcast_file']->isValid()) {
                        $file_path = Helper::fileUpload($content['podcast_file'], 'podcast', getFileName($content['podcast_file']));
                    } else {
                        $file_path = 'uploads/podcast/default.mp3';
                    }
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    $podcast = Podcast::create([
                        'title' => $content['podcast_title'],
                        'description' => $content['podcast_description'],
                        'file' => $file_path,
                        'instructor_id' => $content['podcast_instructor_id'],
                        'course_id' => $course->id,
                    ]);
                    $podcast->tags()->attach($content['tags']);

                    $course->contents()->create([
                        'order' => $newOrder,
                        'type' => 'podcast',
                        'contentable_id' => $podcast->id,
                        'contentable_type' => Podcast::class,
                    ]);
                } else if ($content['type'] == 'evaluation') {
                    // Get the highest order value and increment by 1
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    $evaluation = Evaluation::create([
                        'title' => $content['evaluation_title'],
                    ]);
                    $evaluation->tags()->attach($content['tags']);

                    $course->contents()->create([
                        'order' => $newOrder,
                        'type' => 'evaluation',
                        'contentable_id' => $evaluation->id,
                        'contentable_type' => Evaluation::class
                    ]);

                    foreach ($content['questions'] as $question) {
                        Question::create([
                            'title' => $question['question'],
                            'answer' => $question['answer'],
                            // 'link' => $question['url'],
                            'evaluation_id' => $evaluation->id,
                        ]);
                    }
                }
            }
        }

        //          DB::commit();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Course created successfully!'
            ]);
        }

        return redirect()->route('course.index_new')
            ->with('success', 'Course created successfully!');
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

    public function create_new()
    {
        $tags = Tag::latest()->get();
        $categories = Category::where('status', 'active')->latest()->get();
        $subscriptions = SubscriptionPlan::latest()->get();
        $instructors = Instructor::latest()->get();
        $courses = Course::latest()->get();
        return view('backend.layout.course_new.create', compact('tags', 'categories', 'subscriptions', 'instructors', 'courses'));
    }

    public function edit_new($id)
    {
        $data = Course::findOrFail($id);
        $selectedTagIds = CourseTag::where('course_id', $id)->pluck('tag_id')->toArray();
        $allTags = Tag::all();
        $categories = Category::where('status', 'active')->latest()->get();
        $subscription = SubscriptionPlan::latest()->get();
        $instructors = Instructor::all();
        $content = Content::with([
            'course',
            'contentable' => function ($morph) {
                $morph->morphWith([
                    Video::class => ['video_tags'],
                    Activity::class => ['activity_tags'],
                    Podcast::class => ['podcast_tags'],
                    Evaluation::class => ['evaluation_tags', 'questions'],
                ]);
            }
        ])
            ->where('course_id', $id)
            ->orderBy('order', 'asc')
            ->get();
        // dd([
        //     'data' => $data,
        //     'selectedTagIds' => $selectedTagIds,
        //     'allTags' => $allTags,
        //     'categories' => $categories,
        //     'subscription' => $subscription,
        //     'instructors' => $instructors,
        //     'content' => $content
        // ]);
        return view('backend.layout.course_new.edit', compact('data', 'selectedTagIds', 'allTags', 'categories', 'subscription', 'instructors', 'content'));
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
    public function update_new(Request $request, string $id)
    {
        // dd($request->all());
        $courseMessages = [
            'name.required' => 'Please enter the course name.',
            'name.string' => 'The course name must be valid text.',
            'name.max' => 'The course name may not be greater than 255 characters.',
            'thumbnail.image' => 'The course thumbnail must be an image.',
            'thumbnail.mimes' => 'The course thumbnail must be a jpeg, png, jpg, gif, or svg file.',
            'thumbnail.max' => 'The course thumbnail may not be greater than 2 MB.',
            'tags.required' => 'Please select at least one course tag.',
            'tags.array' => 'Please select valid course tags.',
            'tags.min' => 'Please select at least one course tag.',
            'tags.*.required' => 'Please select at least one course tag.',
            'tags.*.exists' => 'One of the selected course tags is invalid.',
            'subscription_plans_id.required' => 'Please select a subscription plan.',
            'subscription_plans_id.integer' => 'Please select a valid subscription plan.',
            'category_id.required' => 'Please select a category.',
            'category_id.integer' => 'Please select a valid category.',
            'category_id.exists' => 'The selected category is invalid.',
            'description.string' => 'The course description must be valid text.',
        ];

        $contentMessages = [
            'contents.*.type.required' => 'Please select a content type for this block.',
            'contents.*.type.in' => 'Please select a valid content type.',
            'contents.*.video_title.required' => 'Please enter the video title.',
            'contents.*.video_title.string' => 'The video title must be valid text.',
            'contents.*.video_title.max' => 'The video title may not be greater than 100 characters.',
            'contents.*.video_url.string' => 'The uploaded video path is invalid. Please upload the video again.',
            'contents.*.video_image.image' => 'The video thumbnail must be an image.',
            'contents.*.video_image.max' => 'The video thumbnail may not be greater than 2 MB.',
            'contents.*.video_instructor_id.required' => 'Please select a video instructor.',
            'contents.*.video_instructor_id.exists' => 'The selected video instructor is invalid.',
            'contents.*.duration.string' => 'The video duration is invalid.',
            'contents.*.activity_title.required' => 'Please enter the activity title.',
            'contents.*.activity_title.string' => 'The activity title must be valid text.',
            'contents.*.activity_title.max' => 'The activity title may not be greater than 100 characters.',
            'contents.*.activity_description.required' => 'Please enter the activity description.',
            'contents.*.activity_description.string' => 'The activity description must be valid text.',
            'contents.*.activity_image.array' => 'Please upload valid activity images.',
            'contents.*.activity_image.min' => 'Please upload at least one activity image.',
            'contents.*.activity_image.*.image' => 'Each activity image must be an image file.',
            'contents.*.activity_image.*.mimes' => 'Each activity image must be a jpeg, png, jpg, gif, or svg file.',
            'contents.*.activity_image.*.max' => 'Each activity image may not be greater than 2 MB.',
            'contents.*.podcast_title.required' => 'Please enter the podcast title.',
            'contents.*.podcast_title.string' => 'The podcast title must be valid text.',
            'contents.*.podcast_title.max' => 'The podcast title may not be greater than 255 characters.',
            'contents.*.podcast_description.required' => 'Please enter the podcast description.',
            'contents.*.podcast_description.string' => 'The podcast description must be valid text.',
            'contents.*.podcast_instructor_id.required' => 'Please select a podcast instructor.',
            'contents.*.podcast_instructor_id.exists' => 'The selected podcast instructor is invalid.',
            'contents.*.evaluation_title.required' => 'Please enter the evaluation title.',
            'contents.*.evaluation_title.string' => 'The evaluation title must be valid text.',
            'contents.*.evaluation_title.max' => 'The evaluation title may not be greater than 255 characters.',
            'contents.*.questions.required' => 'Please add at least one evaluation question.',
            'contents.*.questions.array' => 'Please add valid evaluation questions.',
            'contents.*.questions.min' => 'Please add at least one evaluation question.',
            'contents.*.questions.*.question.required' => 'Please enter the question text.',
            'contents.*.questions.*.question.string' => 'The question text must be valid text.',
            'contents.*.questions.*.answer.required' => 'Please select the correct answer.',
            'contents.*.questions.*.answer.boolean' => 'Please select a valid correct answer.',
            'contents.*.questions.*.url.string' => 'The question URL must be valid text.',
            'contents.*.questions.*.url.max' => 'The question URL may not be greater than 255 characters.',
            'contents.*.tags.required' => 'Please select at least one tag for this content block.',
            'contents.*.tags.array' => 'Please select valid tags for this content block.',
            'contents.*.tags.min' => 'Please select at least one tag for this content block.',
            'contents.*.tags.*.required' => 'Please select at least one tag for this content block.',
            'contents.*.tags.*.exists' => 'One of the selected content tags is invalid.',
        ];

        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'required|array|min:1',
            'tags.*' => 'required|exists:tags,id',
            'subscription_plans_id' => 'required|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string',
        ], $courseMessages);

        $contents = $request->all()['contents'] ?? [];
        foreach ($contents as $index => $content) {
            $request->validate([
                "contents.$index.type" => 'required|in:video,activity,podcast,evaluation',
            ], $contentMessages);

            if (!isset($content['type'])) {
                continue;
            }
            if ($content['type'] == 'video') {
                $request->validate([
                    "contents.$index.video_title" => 'required|string|max:100',
                    // "contents.$index.video_url" => 'nullable|file|mimes:mp4,ogg,webm|max:512000',
                    "contents.$index.video_url" => 'nullable|string',
                    "contents.$index.video_image" => 'nullable|image|max:2048',
                    "contents.$index.video_instructor_id" => 'required|exists:instructors,id',
                    "contents.$index.duration" => 'nullable|string',
                    "contents.$index.tags" => 'required|array|min:1',
                    "contents.$index.tags.*" => 'required|exists:tags,id',
                ], $contentMessages);
            }
            if ($content['type'] == 'activity') {
                $request->validate([
                    "contents.$index.activity_title" => 'required|string|max:100',
                    "contents.$index.activity_description" => 'required|string',
                    "contents.$index.activity_image" => 'nullable|array|min:1',
                    "contents.$index.activity_image.*" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    "contents.$index.tags" => 'required|array|min:1',
                    "contents.$index.tags.*" => 'required|exists:tags,id'
                ], $contentMessages);
            }

            if ($content['type'] == 'podcast') {
                $request->validate([
                    "contents.$index.podcast_title" => 'required|string|max:255',
                    "contents.$index.podcast_description" => 'required|string',
                    // "contents.$index.podcast_file" => 'required|file|mimes:audio/mpeg,mp3',
                    "contents.$index.podcast_instructor_id" => 'required|exists:instructors,id',
                    "contents.$index.tags" => 'required|array|min:1',
                    "contents.$index.tags.*" => 'required|exists:tags,id'
                ], $contentMessages);
            }

            if ($content['type'] == 'evaluation') {
                $request->validate([
                    "contents.$index.evaluation_title" => 'required|string|max:255',
                    "contents.$index.questions" => 'required|array|min:1',
                    "contents.$index.questions.*.question" => 'required|string',
                    "contents.$index.questions.*.answer" => 'required|boolean',
                    "contents.$index.questions.*.url" => 'nullable|string|max:255',
                    "contents.$index.tags" => 'required|array|min:1',
                    "contents.$index.tags.*" => 'required|exists:tags,id',
                ], $contentMessages);
            }
        }
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
            'category_id' => $request->category_id,
        ]);

        $course->tags()->sync($request->tags);

        // $contents = $request->input('contents', []);
        foreach ($contents as $index => $content) {

            if (!isset($content['type'])) {
                continue; // skip invalid content block
            }

            if ($content['type'] == 'video') {
                if (isset($content['id'])) {
                    $video = Video::findOrFail($content['id']);

                    if (isset($content['video_image']) && $content['video_image']->isValid()) {
                        Helper::fileDelete(public_path($video->image)); // Delete old file
                        $thumbnail_path = Helper::fileUpload($content['video_image'], 'video', getFileName($content['video_image']));
                    } else {
                        $thumbnail_path = $video->image;
                    }

                    // video
                    // video
                    /* if (isset($content['video_url']) && $content['video_url']->isValid()) {
                        if (Storage::disk('public')->exists($video->file)) {
                            Storage::disk('public')->delete($video->file);
                        }
                        $file_path = $content['video_url']->store('course/video', 'public');
                        $duration = $content['duration'];
                    } else {
                        $file_path = $video->file;
                        $duration = $video->duration;
                    } */
                    if (isset($content['video_url']) && !empty($content['video_url'])) {
                        if (Storage::disk('public')->exists($video->file)) {
                            Storage::disk('public')->delete($video->file);
                        }
                        // $file_path = $content["video_url"];
                        $file_path = $content['video_url'];
                        $duration = $content['duration'] ?? '00:00:00';
                    } else {
                        $file_path = $video->file;
                        $duration = $video->duration ?? '00:00:00';
                    }

                    $video->update([
                        'title'         => $content['video_title'],
                        'duration'      => $duration,
                        'file'          => $file_path,
                        'instructor_id' => $content['video_instructor_id'],
                        'image'         => $thumbnail_path,
                    ]);

                    $video->tags()->sync($content['tags']);
                    //order
                    $order = $index + 1;

                    $course->contents()
                        ->where('contentable_id', $content['id'])
                        ->where('type', $content['type'])
                        ->update([
                            'order' => $order,
                        ]);
                } else {
                    // Image
                    if (isset($content['video_image']) && $content['video_image']->isValid()) {
                        $thumbnail_path = Helper::fileUpload($content['video_image'], 'video', getFileName($content['video_image']));
                    } else {
                        $thumbnail_path = 'uploads/video/default.png';
                    }

                    // Store the uploaded file
                    // Store the uploaded file
                    /* if (isset($content['video_url']) && $content['video_url']->isValid()) {
                        if (!Storage::disk('public')->exists('course/video')) {
                            Storage::disk('public')->makeDirectory('course/video');
                        }
                        $file_path = $content['video_url']->store('course/video', 'public');
                    } else {
                        $file_path = 'course/video/default.mp4';
                    } */
                    if (isset($content['video_url'])) {
                        $file_path = $content["video_url"];
                    } else {
                        $file_path = 'course/video/default.mp4';
                    }

                    // $maxOrder = $course->contents()->max('order') ?? 0 ;
                    // $newOrder = $maxOrder + 1;
                    $newOrder = $index + 1;

                    $video = Video::create([
                        'title' => $content['video_title'],
                        'file' => $file_path,
                        'image' => $thumbnail_path,
                        'duration' => $content['duration'] ?? '00:00:00',
                        'instructor_id' => $content['video_instructor_id'],
                    ]);
                    $video->tags()->attach($content['tags']);

                    $course->contents()->create([
                        'order'             => $newOrder,
                        'type'              => 'video',
                        'contentable_id'    => $video->id,
                        'contentable_type'  => Video::class,
                    ]);
                }
            } else if ($content['type'] == 'activity') {
                if (isset($content['id'])) {
                    $activity = Activity::findOrFail($content['id']);
                    $oldImages = $activity->images ?? [];
                    $finalImages = [];

                    $submittedOldImages = $content['old_images'] ?? [];

                    // 1. Delete images that were removed from the UI
                    foreach ($oldImages as $oldImage) {
                        if (!in_array($oldImage, $submittedOldImages)) {
                            Helper::fileDelete(public_path($oldImage));
                        }
                    }

                    // 2. Initialize final images with the ones the user kept
                    foreach ($submittedOldImages as $idx => $path) {
                        $finalImages[$idx] = $path;
                    }

                    // 3. Handle new uploads (replacing old ones or adding new ones)
                    if (isset($content['activity_image']) && is_array($content['activity_image'])) {
                        foreach ($content['activity_image'] as $idx => $image) {
                            if ($image && $image->isValid()) {
                                // If we are replacing an existing image at this index, delete the old one first
                                if (isset($finalImages[$idx])) {
                                    Helper::fileDelete(public_path($finalImages[$idx]));
                                }
                                $finalImages[$idx] = Helper::fileUpload($image, 'activity', getFileName($image));
                            }
                        }
                    }

                    $imagePaths = array_values($finalImages);

                    if (empty($imagePaths)) {
                        $imagePaths[] = 'uploads/activity/default.png';
                    }

                    $activity->update([
                        'title' => $content['activity_title'],
                        'description' => $content['activity_description'],
                        'images' => $imagePaths
                    ]);

                    $activity->tags()->sync($content['tags']);
                    //order
                    $order = $index + 1;

                    $course->contents()
                        ->where('contentable_id', $content['id'])
                        ->where('type', $content['type'])
                        ->update([
                            'order' => $order,
                        ]);
                } else {
                    $imagePaths = [];

                    if (!empty($content['activity_image']) && is_array($content['activity_image'])) {
                        foreach ($content['activity_image'] as $image) {
                            if ($image->isValid()) {
                                $imagePaths[] = Helper::fileUpload(
                                    $image,
                                    'activity',
                                    getFileName($image)
                                );
                            }
                        }
                    }

                    if (empty($imagePaths)) {
                        $imagePaths[] = 'uploads/activity/default.png';
                    }

                    $activity = Activity::create([
                        'title' => $content['activity_title'],
                        'description' => $content['activity_description'],
                        'images' => $imagePaths,
                    ]);


                    $activity->tags()->attach($content['tags']);

                    $newOrder = $index + 1;
                    $course->contents()->create([
                        'order' => $newOrder,
                        'type' => 'activity',
                        'contentable_id' => $activity->id,
                        'contentable_type' => Activity::class,
                    ]);
                }
            } else if ($content['type'] == 'podcast') {
                if (isset($content['id'])) {

                    $podcast = Podcast::findOrFail($content['id']);
                    if (isset($content['podcast_file']) && $content['podcast_file']->isValid()) {
                        if ($podcast->file) {
                            Helper::fileDelete(public_path($podcast->file));
                        }
                        $file_path = Helper::fileUpload($content['podcast_file'], 'podcast', getFileName($content['podcast_file']));
                    } else {
                        $file_path = $podcast->file;
                    }

                    $podcast->update([
                        'title' => $content['podcast_title'],
                        'description' => $content['podcast_description'],
                        'file' => $file_path,
                        'instructor_id' => $content['podcast_instructor_id'],
                    ]);
                    $podcast->tags()->sync($content['tags']);
                    //order
                    $order = $index + 1;

                    $course->contents()
                        ->where('contentable_id', $content['id'])
                        ->where('type', $content['type'])
                        ->update([
                            'order' => $order,
                        ]);
                } else {
                    if (isset($content['podcast_file']) && $content['podcast_file']->isValid()) {
                        $file_path = Helper::fileUpload($content['podcast_file'], 'podcast', getFileName($content['podcast_file']));
                    } else {
                        $file_path = 'uploads/podcast/default.mp3';
                    }

                    $podcast =  Podcast::create([
                        'title'         => $content['podcast_title'],
                        'description'   => $content['podcast_description'],
                        'file'          => $file_path,
                        'instructor_id' => $content['podcast_instructor_id'],
                        'course_id'     => $course->id,
                    ]);
                    $podcast->tags()->attach($content['tags']);
                    $newOrder = $index + 1;
                    $course->contents()->create([
                        'order'             => $newOrder,
                        'type'              => 'podcast',
                        'contentable_id'    => $podcast->id,
                        'contentable_type'  => Podcast::class,
                    ]);
                }
            } else if ($content['type'] == 'evaluation') {
                if (isset($content['id'])) {
                    $evaluation = Evaluation::findOrFail($content['id']);

                    $evaluation->update([
                        'title' => $content['evaluation_title'],
                    ]);

                    $evaluation->tags()->sync($content['tags']);
                    //order
                    $order = $index + 1;

                    $course->contents()
                        ->where('contentable_id', $content['id'])
                        ->where('type', $content['type'])
                        ->update([
                            'order' => $order,
                        ]);

                    foreach ($content['questions'] as $question) {
                        if (isset($question['id'])) {
                            $questions = Question::findOrFail($question['id']);
                            $questions->update([
                                'title' => $question['question'],
                                'answer' => $question['answer'],
                                //'link' => $question['url'],
                            ]);
                        } else {
                            Question::create([
                                'title' => $question['question'],
                                'answer' => $question['answer'],
                                //'link' => $question['url'],
                                'evaluation_id' => $evaluation->id,
                            ]);
                        }
                    }
                } else {
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    $evaluation = Evaluation::create([
                        'title' => $content['evaluation_title'],
                    ]);
                    $evaluation->tags()->attach($content['tags']);

                    $newOrder = $index + 1;
                    $course->contents()->create([
                        'order' => $newOrder,
                        'type' => 'evaluation',
                        'contentable_id' => $evaluation->id,
                        'contentable_type' => Evaluation::class
                    ]);

                    foreach ($content['questions'] as $question) {
                        Question::create([
                            'title' => $question['question'],
                            'answer' => $question['answer'],
                            //'link' => $question['url'],
                            'evaluation_id' => $evaluation->id,
                        ]);
                    }
                }
            }
        }
        flash()->success('Course Updated Successfully');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully!'
            ]);
        }

        return redirect()->route('course.index_new');
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
        $instructors    = Instructor::select('id', 'name')->latest()->get();
        $courses        = Course::latest()->get();
        $tags           = Tag::latest()->get();
        if ($request->ajax()) {

            $data = Content::with(['course'])
                ->where('course_id', $id)
                ->orderBy('order', 'asc') // Order by order column
                ->get();
            $data->loadMorph('contentable', [
                Video::class => ['instructor', 'tags'],
                Activity::class => ['tags'],
                Podcast::class => ['instructor', 'tags'],
                Evaluation::class => ['questions', 'tags'],
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return '<div class="font-bold text-slate-800">' . e($data->contentable?->title ?? 'N/A') . '</div>';
                })
                ->addColumn('type', fn($data) => ucfirst($data->type))
                ->addColumn('summary', function ($data) {
                    $content = $data->contentable;

                    if (!$content) {
                        return '<span class="text-slate-400 italic text-xs">Content not found</span>';
                    }

                    return match ($data->type) {
                        'video' => '<div class="space-y-1 text-sm text-slate-600">'
                            . '<div><span class="font-semibold text-slate-700">Instructor:</span> ' . e($content->instructor?->name ?? 'N/A') . '</div>'
                            . '<div><span class="font-semibold text-slate-700">Duration:</span> ' . e($content->duration ?? 'N/A') . '</div>'
                            . '</div>',
                        'activity' => '<div class="text-sm text-slate-600 max-w-xs">' . e(str($content->description ?? 'No description')->limit(60)) . '</div>',
                        'podcast' => '<div class="space-y-1 text-sm text-slate-600">'
                            . '<div><span class="font-semibold text-slate-700">Instructor:</span> ' . e($content->instructor?->name ?? 'N/A') . '</div>'
                            . '<div>' . e(str($content->description ?? 'No description')->limit(50)) . '</div>'
                            . '</div>',
                        'evaluation' => '<div class="text-sm text-slate-600"><span class="font-semibold text-slate-700">Questions:</span> ' . $content->questions->count() . '</div>',
                        default => '<span class="text-slate-400 italic text-xs">No details</span>',
                    };
                })
                ->addColumn('tags_data', function ($data) {
                    $tags = $this->contentTags($data->contentable);

                    if ($tags->isEmpty()) {
                        return '<span class="text-slate-400 italic text-xs">No tags</span>';
                    }

                    return $tags->take(3)->map(function ($tag) {
                        return '<span class="inline-flex px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-semibold">' . e($tag) . '</span>';
                    })->implode(' ') . ($tags->count() > 3 ? ' <span class="text-slate-400 text-xs">+' . ($tags->count() - 3) . '</span>' : '');
                })
                ->addColumn('asset_summary', function ($data) {
                    $content = $data->contentable;

                    if (!$content) {
                        return '<span class="text-slate-400">N/A</span>';
                    }

                    return match ($data->type) {
                        'video' => $content->file
                            ? '<span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold">Video file</span>'
                            : '<span class="text-slate-400">No video</span>',
                        'activity' => '<span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-orange-50 text-orange-700 text-xs font-bold">' . count($content->images ?? []) . ' image(s)</span>',
                        'podcast' => $content->file
                            ? '<span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 text-xs font-bold">Audio file</span>'
                            : '<span class="text-slate-400">No audio</span>',
                        'evaluation' => '<span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">' . $content->questions->count() . ' question(s)</span>',
                        default => '<span class="text-slate-400">N/A</span>',
                    };
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

                    return '<div role="group" class="flex items-center justify-end gap-2 whitespace-nowrap">

                    <button data-modal-open="edit-question" class="edit flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" data-id="' . $data->id . '">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" height="18" width="18" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" ><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577
                        16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    </button>

                    <a href="javascript:void(0);" class="edit-video-btn flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-blue-500 hover:bg-blue-100 dark:bg-zink-600 dark:text-zink-200"
                        data-id="' . $data->contentable_id . '" data-type="' . $modelType . '" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                            <path d="m15 5 4 4"></path>
                        </svg>
                    </a>

                    <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $data->id . ')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            <line x1="10" x2="10" y1="11" y2="17"></line>
                            <line x1="14" x2="14" y1="11" y2="17"></line>
                        </svg>
                    </a>
                </div>';
                })
                ->rawColumns(['title', 'summary', 'tags_data', 'asset_summary', 'action'])
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
        $data = Content::findOrFail($id);
        $data->loadMorph('contentable', [
            Video::class => ['instructor', 'tags'],
            Activity::class => ['tags'],
            Podcast::class => ['instructor', 'tags'],
            Evaluation::class => ['questions', 'tags'],
        ]);

        if (!$data || !$data->contentable) {
            return response()->json(['success' => false, 'message' => 'Content not found.']);
        }

        $content = $data->contentable;
        $details = [
            'type' => ucfirst($data->type),
            'title' => $content->title ?? 'N/A',
            'description' => $content->description ?? null,
            'tags' => $this->contentTags($content)->values(),
        ];

        if ($data->type == 'video') {
            $details['description'] = null;
            $details['instructor'] = $content->instructor?->name;
            $details['duration'] = $content->duration;
            $details['video_url'] = $this->assetUrl($content->file);
            $details['video_image'] = $this->assetUrl($content->image);
        } elseif ($data->type == 'podcast') {
            $details['instructor'] = $content->instructor?->name;
            $details['podcast_file'] = $this->assetUrl($content->file);
        } elseif ($data->type == 'activity') {
            $details['images'] = array_map(function ($img) {
                return $this->assetUrl($img);
            }, $content->images ?? []);
        } elseif ($data->type == 'evaluation') {
            $details['description'] = null;
            $details['questions'] = $content->questions->map(function ($q) {
                return [
                    'title' => $q->title,
                    'answer' => $q->answer ? 'Yes' : 'No',
                    'link' => $q->link
                ];
            });
        }

        return response()->json([
            'success' => true,
            'data' => $details
        ]);
    }

    private function contentTags(?Model $content): \Illuminate\Support\Collection
    {
        if (!$content || !method_exists($content, 'tags')) {
            return collect();
        }

        return $content->relationLoaded('tags')
            ? $content->tags->pluck('title')
            : $content->tags()->pluck('title');
    }

    private function assetUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return str_starts_with($path, 'uploads/') ? asset($path) : asset('storage/' . $path);
    }
}
