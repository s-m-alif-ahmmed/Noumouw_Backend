<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Course;
// use DB;
use App\Models\Tag;
use App\Models\VideoTag;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Helpers\Helper;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;
use App\Traits\ChunkFileUpload;
// use RahulHaque\Filepond\Facades\Filepond;

class VideoController extends Controller
{
    use ChunkFileUpload;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Video::with('instructor','content.course')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('file', function ($data) {
                    $url = $data->privateVideo();
                    return  ' <video controls width="200"><source src="'.$url.'" type="video/mp4">Your browser does not support the video tag.</video>';
                })
                ->addColumn('course_name', function ($data) {
                    return $data->content?->course?->name ? $data->content?->course?->name : 'N/A';
                })
                ->addColumn('instructor_name', function ($data) {
                    return $data->instructor ? $data->instructor->name : 'N/A';
                })
                ->addColumn('status', function ($data) {
                    $status = '<label class="inline-flex items-center cursor-pointer">';
                    $status .= '<input onclick="showStatusChangeAlert('.$data->id.')" type="checkbox" value="" class="sr-only peer" id="customSwitch'.$data->id.'" name="status"';
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
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="'.route('video.edit', $data->id).'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                        </a>
                        <a href="#!" onclick="showDeleteConfirm('.$data->id.')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </a>
                    </div>';
                })
                ->rawColumns(['file', 'status','course_name', 'action'])
                ->make(true);
        }

        return view('backend.layout.video.index');
    }


    public function create(Request $request)
    {
        $data = Instructor::select('id','name')->latest()->get();
        $tags = Tag::latest()->get();
        $courses = Course::all();
        $selectedCourse = null;
        if ($request->has('id')) {
            $selectedCourse = Course::find($request->id);
        }

        return view('backend.layout.video.create',compact('data', 'tags', 'courses', 'selectedCourse'));
    }


    public function ajaxStore(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:100',
            // 'file'          => 'required|string', // Changed for FilePond
            'file_path'     => 'required|string', // New for custom chunk upload
            'course_id'     => 'required|exists:courses,id',
            'instructor_id' => 'required|exists:instructors,id',
            'tags'          => 'required|array',
            'tags.*'        => 'required|exists:tags,id',
        ]);

        try {
            // Validate duration - allow HH:MM:SS or numeric seconds
            $duration = $request->duration;
            if (!$duration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video duration is required'
                ]);
            }

            // If it's numeric (seconds), convert to HH:MM:SS
            if (is_numeric($duration)) {
                $duration = gmdate("H:i:s", (int) round($duration));
            }

            // Image
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $thumbnail_path = Helper::fileUpload($request->file('image'), 'video', getFileName($request->file('image')));
            } else {
                $thumbnail_path = 'uploads/video/default.png';
            }

            // Store the uploaded file
            /* if ($request->filled('file')) {
                $file_path = Filepond::field($request->file)->moveTo('course/video/' . time() . '_' . uniqid())['location'];
            } else {
                $file_path = 'course/video/default.mp4';
            } */
            $file_path = $request->file_path ?? 'course/video/default.mp4';

            DB::beginTransaction();

            $course = Course::find($request->course_id);
            if (!$course) {
                return response()->json(['success' => false, 'message' => 'Course not found']);
            }

            // Get the highest order value and increment by 1
            $maxOrder = $course->contents()->max('order') ?? 0;
            $newOrder = $maxOrder + 1;

            $formattedDuration = $duration;

            // Create the video
            $video = Video::create([
                'title' => $request->title,
                'duration' => $formattedDuration,
                'file' => $file_path,
                'image' => $thumbnail_path,
                'instructor_id' => $request->instructor_id,
            ]);

            // Clean and attach tags
            $tagIds = collect($request->tags)->filter()->values()->toArray();
            $video->tags()->attach($tagIds);

            // Create course content
            $course->contents()->create([
                'order' => $newOrder,
                'type' => 'video',
                'contentable_id' => $video->id,
                'contentable_type' => Video::class,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Video created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }


    public function store(Request $request)
    {

        $request->validate([
            'title'         => 'required|string|max:100',
            'file'          => 'required|mimes:mp4,ogg,webm',
            'course_id'     => 'required|exists:courses,id',
            'instructor_id' => 'required|exists:instructors,id',
            'tags.*'        => 'required|exists:tags,id',
        ]);

        try {
            if (!$request->duration || !isUnsignedBigInt($request->duration) || $request->duration < 0) {
                redirect()->back()->withInput()->withErrors([
                    'file'=> 'Invalid duration of this video',
                ]);
            }
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                if (!Storage::disk('private')->exists('course/video')) {
                    Storage::disk('private')->makeDirectory('course/video');
                }
                $file_path = $request->file('file')->store('course/video', 'private');
            } else {
                $file_path = Storage::disk('private')->url('course/video/default.mp4');
            }
            DB::beginTransaction();
            $course = Course::find($request->course_id);

            // Get the highest order value and increment by 1
            $maxOrder = $course->contents()->max('order') ?? 0;
            $newOrder = $maxOrder + 1;

            $video = Video::create([
                'title'         => $request->title,
                'duration'      => $request->duration,
                'file'          => $file_path,
                'instructor_id' => $request->instructor_id,
            ]);
            $video->tags()->attach($request->tags);

            $course->contents()->create([
                'order'             => $newOrder,
                'type'              =>'video',
                'contentable_id'    => $video->id,
                'contentable_type'  => Video::class,
            ]);

            DB::commit();

            flash()->success('Video created successfully');
            return redirect()->route('video.index');
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data               = Video::with(['content.course'])->findOrFail($id);
        $instructors        = Instructor::all();
        $selectedTagIds     = VideoTag::where('video_id', $id)->pluck('tag_id')->toArray();
        $allTags            = Tag::all();
        $courses            = Course::all();
        return view('backend.layout.video.edit', compact('data', 'allTags', 'selectedTagIds', 'instructors','courses'));
    }

    public function editData($id)
    {

        $video = Video::with('tags', 'content.course')->findOrFail($id);
        $data = Content::where('contentable_id', $id)->where('contentable_type', Video::class)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $video->id,
                'title'         => $video->title,
                'instructor_id' => $video->instructor_id,
                'course_id'     => $data->course_id,
                'tags'          => $video->tags->pluck('id'),
                'file_url'      => str_starts_with($video->file, 'uploads/') ? asset($video->file) : asset('storage/' . $video->file),
                'image'         => str_starts_with($video->image, 'uploads/') ? asset($video->image) : asset('storage/' . $video->image)
            ]
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'             => 'required|string|max:100',
            // 'file'              => 'nullable|string', // Changed for FilePond
            'file_path'         => 'nullable|string', // New for custom chunk upload
            'course_id'         => 'required|exists:courses,id',
            'instructor_id'     => 'required|exists:instructors,id',
            'tags.*'            => 'exists:tags,id',
        ]);

        try {
            // Validate duration if a new file is uploaded
            if ($request->filled('file')) {
                $duration = $request->duration;
                if (!$duration) {
                    return response()->json(['success' => false, 'message' => 'Video duration is required']);
                }
                if (is_numeric($duration)) {
                    $duration = gmdate("H:i:s", (int) round($duration));
                }
            } else {
                $video = Video::findOrFail($id);
                $duration = $video->duration;
            }
            $video      = Video::findOrFail($id);
            $course     = Course::find($request->course_id);

            // image
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                Helper::fileDelete(public_path($video->image)); // Delete old file
                $thumbnail_path = Helper::fileUpload($request->file('image'), 'course', getFileName($request->file('image')));
            } elseif ($request->input('remove_image') == 1) {
                Helper::fileDelete(public_path($video->image));
                $thumbnail_path = 'uploads/video/default.png';
            } else {
                $thumbnail_path = $video->image;
            }

            // video
            if ($request->filled('file_path')) {
                if (Storage::disk('public')->exists($video->file)) {
                    Storage::disk('public')->delete($video->file);
                }
                // $file_path = Filepond::field($request->file)->moveTo('course/video/' . time() . '_' . uniqid())['location'];
                $file_path = $request->file_path;
                $duration = $request->duration;
            } elseif ($request->input('remove_file') == 1) {
                if (Storage::disk('public')->exists($video->file)) {
                    Storage::disk('public')->delete($video->file);
                }
                $file_path = 'course/video/default.mp4';
                $duration = '00:00:00';
            } else {
                $file_path = $video->file;
                $duration = $video->duration;
            }
            DB::beginTransaction();

            $video->update([
                'title'         => $request->title,
                'duration'      => $duration,
                'file'          => $file_path,
                'instructor_id' => $request->instructor_id,
                'image'         => $thumbnail_path,
            ]);

            $video->tags()->sync($request->tags);

            // Ensure content exists before updating
            $content = $video->content;
            if ($content) {
                $content->update([
                    'course_id' => $request->course_id
                ]);
            }

            // Update related content, if exists
            if ($request->course_id) {
                if ($video->content) {
                    // Update the existing content record
                    $video->content->update([
                        'course_id' => $request->course_id,
                    ]);
                } else {

                    // Get the highest order value and increment by 1
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    // Create a new content record
                    $video->content()->create([
                        'order' => $newOrder,
                        'course_id'       => $request->course_id,
                        'type'            => 'video', // Set the appropriate type
                        'contentable_id'  => $video->id,
                        'contentable_type' => Video::class,
                    ]);
                }
            }

            DB::commit();
            flash()->success('Video Updated successfully');

            return response()->json([
                'success' => true,
                'message' => 'Video Updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Something went wrong');
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }


    public function destroy(string $id)
    {

        $data = Video::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
        if (Storage::disk('private')->exists($data->file)) {
            Storage::disk('private')->delete($data->file);
        }
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully!',
        ]);
    }

    public function status($id)
    {

        $Video = Video::findOrFail($id);

        if (! $Video) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
        if ($Video->status == 'active') {
            $Video->status = 'inactive';
        } else {
            $Video->status = 'active';
        }
        $Video->save();

        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.',
        ], 200);
    }

    public function chunkUpload(Request $request)
    {
        $fileName = $request->input('file_name', $request->header('X-File-Name'));
        $folder = 'course/video';

        $param = [
            'chunk'        => $request->file('file'),
            'index'        => $request->input('index'),
            'total_chunks' => $request->input('total_chunks'),
            'temp_id'      => $request->input('temp_id'),
        ];

        $result = $this->handleChunkedUploadPublic($fileName, $param, $folder);

        return response()->json($result);
    }
}
