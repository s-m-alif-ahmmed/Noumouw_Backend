<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Content;
use App\Models\Course;
use App\Models\Evaluation;
use App\Models\EvaluationTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Http\Controllers\Controller;


class EvaluationController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = Evaluation::with('content.course')->latest();

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
                    return '<div role="group" style="gap: 10px;display: flex; text-alighn">
                        <a class="flex items-center justify-center w-8 h-8 transition-all rounded-md bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200" href="' . route('evaluation.edit', $data->id) . '">
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

        $courses = Course::select('id', 'name', 'thumbnail')->latest()->get();

        return view('backend.layout.evaluation.index', compact('courses'));
    }

    public function create(Request $request)
    {
        $courses = Course::select('id','name','thumbnail')->latest()->get();
        $tags = Tag::latest()->get();
        $selectedCourse = null;
        if ($request->has('id')) {
            $selectedCourse = Course::find($request->id);
        }
        return view('backend.layout.evaluation.create',compact('courses', 'tags', 'selectedCourse'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'tags.*' => 'required|exists:tags,id',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.answer' => 'required|integer',
            //'questions.*.url' => 'nullable|string|max:255',
        ]);
        try {

            DB::beginTransaction();

            $course = Course::find($request->course_id);

            // Get the highest order value and increment by 1
            $maxOrder = $course->contents()->max('order') ?? 0;
            $newOrder = $maxOrder + 1;

            $evaluation = Evaluation::create([
                'title' => $request->title
            ]);
            $evaluation->tags()->attach($request->tags);

            $course->contents()->create([
                'order' => $newOrder,
                'type' => 'evaluation',
                'contentable_id' => $evaluation->id,
                'contentable_type' => Evaluation::class
            ]);

            foreach ($request->questions as $question) {
                Question::create([
                    'title' => $question['question'],
                    'answer' => $question['answer'],
                    //'link' => $question['url'],
                    'evaluation_id' => $evaluation->id,
                ]);
            }

            DB::commit();

            flash()->success('Evaluation created successfully');

            return response()->json([
                'success' => true,
                'message' => 'Evaluation created successfully'
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            flash()->error($exception->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $exception->getMessage()
            ]);
        }
    }

    public function edit(string $id)
    {
        $data = Evaluation::findOrFail($id);
        $selectedTagIds = EvaluationTag::where('evaluation_id', $id)->pluck('tag_id')->toArray();
        $allTags = Tag::all();
        $courses = Course::select('id','name','thumbnail')->latest()->get();

        return view('backend.layout.evaluation.edit', compact('data', 'allTags', 'selectedTagIds', 'courses'));

    }

    public function editData($id)
    {
        $evaluation = Evaluation::with('tags', 'content.course')->findOrFail($id);
        $data       = Content::where('contentable_id', $id)->where('contentable_type', Evaluation::class)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $evaluation->id,
                'title'         => $evaluation->title,
                'course_id'     => $data->course_id,
                'tags'          => $evaluation->tags->pluck('id'),
                'questions'     => $evaluation->questions,
            ]
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'tags.*' => 'exists:tags,id',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.answer' => 'required|integer',
            //'questions.*.url' => 'nullable|string|max:255',
        ]);

        try {

            $evaluation = Evaluation::findOrFail($id);

            if (empty($evaluation)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation not found.',
                ]);
            }

            DB::beginTransaction();
            $course = Course::find($request->course_id);

            $evaluation->update([
                'title' => $request->title,
                'course_id' => $request->course_id,
            ]);

            $evaluation->tags()->sync($request->tags);

            // Update related content, if exists
            if ($request->course_id) {
                if ($evaluation->content) {
                    // Update the existing content record
                    $evaluation->content->update([
                        'course_id' => $request->course_id,
                    ]);
                } else {

                    // Get the highest order value and increment by 1
                    $maxOrder = $course->contents()->max('order') ?? 0;
                    $newOrder = $maxOrder + 1;

                    // Create a new content record
                    $evaluation->content()->create([
                        'course_id'       => $request->course_id,
                        'type'            => 'evaluation', // Set the appropriate type
                        'contentable_id'  => $evaluation->id,
                        'contentable_type' => Evaluation::class,
                    ]);
                }
            }

            // Sync questions
            // Simplest way: Delete old and create new
            $evaluation->questions()->delete();
            foreach ($request->questions as $question) {
                Question::create([
                    'title' => $question['question'],
                    'answer' => $question['answer'],
                    //'link' => $question['url'],
                    'evaluation_id' => $evaluation->id,
                ]);
            }

            DB::commit();

            flash()->success('Evaluation Updated successfully');

            return response()->json([
                'success' => true,
                'message' => 'Evaluation Updated successfully'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            flash()->error('Somethings went wrong');
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $exception->getMessage()
            ]);
        }
    }

    public function destroy(string $id)
    {

        $data = Evaluation::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation not found.',
            ], 404);
        }

        $data->delete();
        $data->content()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evaluation deleted successfully!',
        ]);
    }


    public function status(Request $request, string $id)
    {

        $evaluation = Evaluation::findOrFail($id);
        if (! $evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }
        if ($evaluation->status == 'active') {
            $evaluation->status = 'inactive';
        } else {
            $evaluation->status = 'active';
        }
        $evaluation->save();

        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.',
        ], 200);
    }
}
