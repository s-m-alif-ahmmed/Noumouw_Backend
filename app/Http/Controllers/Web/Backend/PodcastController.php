<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Content;
use App\Models\Course;
use App\Helpers\Helper;
use App\Models\Podcast;
use App\Models\Instructor;
use App\Models\PodcastTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class PodcastController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Podcast::with('instructor','content.course')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('course_name' , function ($data){
                    return $data->content?->course?->name ? $data->content?->course?->name : 'N/A';
                   })
                ->addColumn('file', function ($data) {
                    $url = asset($data->file);
                    $extension = pathinfo($data->file, PATHINFO_EXTENSION);

                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        return '<img src="'.$url.'" alt="Image" width="50px" height="50px" style="margin-left:20px;">';
                    } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
                        return '<audio controls style="width: 200px; margin-left: 20px;">
                                    <source src="'.$url.'" type="audio/'.$extension.'">
                                    Your browser does not support the audio element.
                                </audio>';
                    } elseif (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                        return '<video controls width="150px" height="100px" style="margin-left:20px;">
                                    <source src="'.$url.'" type="video/'.$extension.'">
                                    Your browser does not support the video element.
                                </video>';
                    } else {
                        return '<a href="'.$url.'" target="_blank" style="margin-left:20px;">Download File</a>';
                    }
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
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="'.route('podcast.edit', $data->id).'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path><path d="m15 5 4 4"></path></svg>
                        </a>
                        <a href="#!" onclick="showDeleteConfirm('.$data->id.')" class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </a>
                    </div>';
                })
                ->rawColumns(['course_name','file', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.podcast.index');
    }


    public function create(Request $request)
    {
        $data = Instructor::select('id', 'name')->get();

        $tags = Tag::latest()->get();
        $courses = Course::select('id','name','thumbnail')->latest()->get();
        $selectedCourse = null;
        if ($request->has('id')) {
            $selectedCourse = Course::find($request->id);
        }
      return view('backend.layout.podcast.create',compact('data', 'tags', 'courses', 'selectedCourse'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|mimes:audio/mpeg,mp3|max:10240',
            'instructor_id' => 'required|exists:instructors,id',
            'course_id' => 'required|exists:courses,id',
            'tags.*' => 'required|exists:tags,id',
        ]);

        try{

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file_path = Helper::fileUpload($request->file('file'), 'podcast', getFileName($request->file('file')));
            }else{
                $file_path = 'uploads/podcast/default.mp3';
            }

            DB::beginTransaction();

            $course = Course::find($request->course_id);

            // Get the highest order value and increment by 1
            $maxOrder = $course->contents()->max('order') ?? 0;
            $newOrder = $maxOrder + 1;

            $podcast =  Podcast::create([
                'title' => $request->title,
                'description' => $request->description,
                'file' => $file_path,
                'instructor_id' => $request->instructor_id,
                'course_id' => $request->course_id
            ]);
            $podcast->tags()->attach($request->tags);

            $course->contents()->create([
                'order' => $newOrder,
                'type' => 'podcast',
                'contentable_id' => $podcast->id,
                'contentable_type' => Podcast::class,
            ]);

            DB::commit();
            flash()->success('Podcast created successfully');
            //return redirect()->route('podcast.index');
            return response()->json([
                'success' => true,
                'message' => 'Podcast created successfully'
            ]);

        }catch(\Exception $e){
            DB::rollBack();
            flash()->error($e->getMessage());
            //return redirect()->back();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }

    }


    public function edit($id)
    {

        $data = Podcast::findOrFail($id);
        $selectedTagIds = PodcastTag::where('podcast_id', $id)->pluck('tag_id')->toArray();
        $allTags = Tag::all();
        $courses = Course::all();
        $instructors = Instructor::all();
        return view('backend.layout.podcast.edit', compact('data', 'allTags', 'selectedTagIds', 'instructors','courses'));
    }

    public function editData($id)
    {

        $podcast = Podcast::with('tags', 'content.course')->findOrFail($id);
        $data = Content::where('contentable_id', $id)->where('contentable_type', Podcast::class)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $podcast->id,
                'title'         => $podcast->title,
                'instructor_id' => $podcast->instructor_id,
                'description'   => $podcast->description,
                'course_id'     => $data->course_id,
                'tags'          => $podcast->tags->pluck('id'),
                'file_url'      => asset($podcast->file),
            ]
        ]);
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'nullable|file',
            'instructor_id' => 'nullable|exists:instructors,id',
            'tags.*' => 'exists:tags,id',
        ]);

        try{

            $podcast = Podcast::findOrFail($id);

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                if ($podcast->file) {
                    Helper::fileDelete(public_path($podcast->file));
                }
                $file_path = Helper::fileUpload($request->file('file'), 'podcast', getFileName($request->file('file')));
            }else{
                $file_path = $podcast->file;
            }

            DB::beginTransaction();
            $course = Course::find($request->course_id);

            $podcast->update([
                'title' => $request->title,
                'description' => $request->description,
                'file' => $file_path,
                'instructor_id' => $request->instructor_id,
            ]);

            $podcast->tags()->sync($request->tags);

            // Update related content, if exists
            if ($request->course_id) {
                if ($podcast->content) {
                    // Update the existing content record
                    $podcast->content->update([
                        'course_id' => $request->course_id,
                    ]);
                } else {
                    // Get the highest order value and increment by 1
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    // Create a new content record
                    $podcast->content()->create([
                        'order' => $newOrder,
                        'course_id'       => $request->course_id,
                        'type'            => 'podcast', // Set the appropriate type
                        'contentable_id'  => $podcast->id,
                        'contentable_type' => Podcast::class,
                    ]);
                }
            }

            DB::commit();

            flash()->success('Podcast Updated successfully');
            return response()->json([
                'success' => true,
                'message' => 'Podcast Updated successfully'
            ]);

        }catch(\Exception $e){
            DB::rollBack();
            flash()->error('Somethings went wrong');
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }


    public function destroy($id)
    {
         try{
            $data = Podcast::findOrFail($id);
            if ($data->file) {
                Helper::fileDelete(public_path($data->file));
            }

            $data->delete();
            $data->content()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Podcast deleted successfully!',
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Somethings went wrong'
                ], 500);
        }


    }

    public function status($id)
    {
        $podcast = Podcast::findOrFail($id);
        if (! $podcast) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
        if ($podcast->status == 'active') {
            $podcast->status = 'inactive';
        } else {
            $podcast->status = 'active';
        }
        $podcast->save();

        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.',
        ], 200);
    }

}
