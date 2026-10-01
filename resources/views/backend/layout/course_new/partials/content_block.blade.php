<div class="details-item p-6 mt-6 border border-slate-200">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
        <div class="flex items-center gap-3">
            <div class="drag-handle p-2 hover:bg-slate-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="5" r="1" />
                    <circle cx="9" cy="12" r="1" />
                    <circle cx="9" cy="19" r="1" />
                    <circle cx="15" cy="5" r="1" />
                    <circle cx="15" cy="12" r="1" />
                    <circle cx="15" cy="19" r="1" />
                </svg>
            </div>
            <span class="font-bold text-slate-700">Content Block #{{ is_numeric($index) ? $index + 1 : 'New' }}</span>
        </div>
        <button type="button"
            class="remove-item p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
            title="Remove Block">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18" />
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                <line x1="10" x2="10" y1="11" y2="17" />
                <line x1="14" x2="14" y1="11" y2="17" />
            </svg>
        </button>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div>
            <label class="form-label">Content Type <span class="text-red-500">*</span></label>
            <select name="contents[{{ $index }}][type]" class="form-control-modern content-type" required>
                <option selected disabled value="">Select a Content Type</option>
                <option value="video" @if (old("contents.$index.type", $content['type'] ?? '') == 'video') selected @endif>🎥 Video Lecture</option>
                <option value="activity" @if (old("contents.$index.type", $content['type'] ?? '') == 'activity') selected @endif>📝 Hands-on Activity</option>
                <option value="podcast" @if (old("contents.$index.type", $content['type'] ?? '') == 'podcast') selected @endif>🎙️ Podcast/Audio</option>
                <option value="evaluation" @if (old("contents.$index.type", $content['type'] ?? '') == 'evaluation') selected @endif>📊 Knowledge Evaluation
                </option>
            </select>
        </div>
    </div>

    <div class="content-forms mt-8">
        {{-- Video Form --}}
        <div
            class="content-form {{ old("contents.$index.type", $content['type'] ?? '') == 'video' ? '' : 'hidden' }} video grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="form-label">Video Title <span class="text-red-500">*</span></label>
                    <input type="text" class="form-control-modern" name="contents[{{ $index }}][video_title]"
                        value="{{ old("contents.$index.video_title", $content['video_title'] ?? '') }}"
                        placeholder="Enter video title" required>
                </div>
                <div>
                    <label class="form-label">Instructor <span class="text-red-500">*</span></label>
                    <select name="contents[{{ $index }}][video_instructor_id]" class="form-control-modern"
                        required>
                        <option selected disabled value="">Select Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @if (old("contents.$index.video_instructor_id", $content['video_instructor_id'] ?? '') == $instructor->id) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Tags <span class="text-red-500">*</span></label>
                    <select name="contents[{{ $index }}][tags][]" multiple class="content-tags tags-required">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old("contents.$index.tags", $content['tags'] ?? []))) selected @endif>
                                {{ $tag->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-6">
                <div>
                    <label class="form-label">Video File <span class="text-red-500">*</span></label>
                    <input type="hidden" name="contents[{{ $index }}][duration]" class="video-duration-input"
                        value="{{ old("contents.$index.duration", $content['duration'] ?? '') }}">
                    {{-- <input type="file" class="dropify" name="contents[{{ $index }}][video_url]" data-height="120" onchange="calculateVideoDuration(this)" required> --}}
                    {{-- <input type="file" class="video-filepond" name="contents[{{ $index }}][video_url]"
                        required> --}}
                    <input type="file" class="dropify video-chunk-input" data-height="120"
                        onchange="calculateVideoDuration(this)" required>
                    <input type="hidden" name="contents[{{ $index }}][video_url]" class="video-final-path">

                    <div class="chunk-progress-container hidden mt-2">
                        <div class="flex justify-between mb-1">
                            <span class="text-[10px] font-medium text-blue-700">Uploading...</span>
                            <span class="text-[10px] font-medium text-blue-700 chunk-upload-percentage">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full chunk-upload-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="form-label">Video Thumbnail <span class="text-red-500">*</span></label>
                    <input type="file" class="dropify" name="contents[{{ $index }}][video_image]"
                        data-height="120" required>
                </div>
            </div>
        </div>

        {{-- Activity Form --}}
        <div
            class="content-form {{ old("contents.$index.type", $content['type'] ?? '') == 'activity' ? '' : 'hidden' }} activity grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="form-label">Activity Title <span class="text-red-500">*</span></label>
                    <input class="form-control-modern" name="contents[{{ $index }}][activity_title]"
                        value="{{ old("contents.$index.activity_title", $content['activity_title'] ?? '') }}"
                        placeholder="Enter activity title" required>
                </div>
                <div>
                    <label class="form-label">Description <span class="text-red-500">*</span></label>
                    <textarea name="contents[{{ $index }}][activity_description]" class="form-control-modern" rows="4"
                        placeholder="Describe the activity..." required>{{ old("contents.$index.activity_description", $content['activity_description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Tags <span class="text-red-500">*</span></label>
                    <select name="contents[{{ $index }}][tags][]" multiple class="content-tags tags-required">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old("contents.$index.tags", $content['tags'] ?? []))) selected @endif>
                                {{ $tag->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-4">
                    <label class="form-label mb-0">Activity Images</label>
                    <button type="button"
                        class="btn-premium py-1.5 px-3 text-xs bg-green-50 text-green-600 border border-green-100 hover:bg-green-100 edit-add-image">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Add Image
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-4 activity-images-wrapper">
                    @php
                        $oldImages = old("contents.$index.activity_image", []);
                        $imageCount = count($oldImages) > 0 ? count($oldImages) : 1;
                    @endphp
                    @for ($i = 0; $i < $imageCount; $i++)
                        <div class="single-gallery-image relative">
                            <button type="button"
                                class="remove-gallery-image absolute -top-2 -right-2 z-10 size-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">×</button>
                            <input type="file" name="contents[{{ $index }}][activity_image][]"
                                class="dropify" data-height="120" required>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Podcast Form --}}
        <div
            class="content-form {{ old("contents.$index.type", $content['type'] ?? '') == 'podcast' ? '' : 'hidden' }} podcast grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <label class="form-label">Podcast Title <span class="text-red-500">*</span></label>
                    <input type="text" class="form-control-modern"
                        name="contents[{{ $index }}][podcast_title]"
                        value="{{ old("contents.$index.podcast_title", $content['podcast_title'] ?? '') }}"
                        placeholder="Enter podcast title" required>
                </div>
                <div>
                    <label class="form-label">Instructor <span class="text-red-500">*</span></label>
                    <select name="contents[{{ $index }}][podcast_instructor_id]" class="form-control-modern"
                        required>
                        <option selected disabled value="">Select Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @if (old("contents.$index.podcast_instructor_id", $content['podcast_instructor_id'] ?? '') == $instructor->id) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Description <span class="text-red-500">*</span></label>
                    <textarea name="contents[{{ $index }}][podcast_description]" class="form-control-modern" rows="4"
                        placeholder="Briefly describe the audio content..." required>{{ old("contents.$index.podcast_description", $content['podcast_description'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="space-y-6">
                <div>
                    <label class="form-label">Audio File <span class="text-red-500">*</span></label>
                    <input type="file" class="dropify" name="contents[{{ $index }}][podcast_file]"
                        data-height="150" required>
                </div>
                <div>
                    <label class="form-label">Tags <span class="text-red-500">*</span></label>
                    <select name="contents[{{ $index }}][tags][]" multiple class="content-tags tags-required">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old("contents.$index.tags", $content['tags'] ?? []))) selected @endif>
                                {{ $tag->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Evaluation Form --}}
        <div
            class="content-form {{ old("contents.$index.type", $content['type'] ?? '') == 'evaluation' ? '' : 'hidden' }} evaluation flex flex-col gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Evaluation Title <span class="text-red-500">*</span></label>
                    <input class="form-control-modern" name="contents[{{ $index }}][evaluation_title]"
                        value="{{ old("contents.$index.evaluation_title", $content['evaluation_title'] ?? '') }}"
                        placeholder="e.g. Mid-term Quiz" required>
                </div>
                <div>
                    <label class="form-label">Tags <span class="text-red-500">*</span></label>
                    <select name="contents[{{ $index }}][tags][]" multiple class="content-tags tags-required">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old("contents.$index.tags", $content['tags'] ?? []))) selected @endif>
                                {{ $tag->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="questions-wrapper space-y-6 pt-6 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <h5 class="font-bold text-slate-700 text-sm">Question List</h5>
                    <button type="button"
                        class="btn-premium py-1.5 px-3 text-xs bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 addMore">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        Add New Question
                    </button>
                </div>

                @php
                    $oldQuestions = old(
                        "contents.$index.questions",
                        $content['questions'] ?? [['question' => '', 'url' => '', 'answer' => '1']],
                    );
                @endphp
                @foreach ($oldQuestions as $qIndex => $question)
                    <div class="question-item p-5 bg-white border border-slate-200 rounded-xl relative">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-8">
                                <label class="form-label text-xs">Question Text <span
                                        class="text-red-500">*</span></label>
                                <textarea name="contents[{{ $index }}][questions][{{ $qIndex }}][question]" class="form-control-modern"
                                    rows="3" placeholder="Enter your question here..." required>{{ $question['question'] ?? '' }}</textarea>
                            </div>
                            <div class="md:col-span-4 flex flex-col justify-between">
                                <div>
                                    <label class="form-label text-xs">Correct Answer <span
                                            class="text-red-500">*</span></label>
                                    <select
                                        name="contents[{{ $index }}][questions][{{ $qIndex }}][answer]"
                                        class="form-control-modern" required>
                                        <option value="1" @if (($question['answer'] ?? '') == '1') selected @endif>✅
                                            Yes / Correct</option>
                                        <option value="0" @if (($question['answer'] ?? '') == '0') selected @endif>❌ No
                                            / Incorrect</option>
                                    </select>
                                </div>
                                @if ($qIndex > 0)
                                    <div class="flex justify-end mt-4">
                                        <button type="button"
                                            class="remove-question text-red-500 hover:text-red-700 text-xs font-bold flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            </svg>
                                            Remove Question
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


@push('scripts')
@endpush
