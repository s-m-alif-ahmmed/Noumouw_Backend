
<div class="w-full">
    <label for="{{$name}}" class="inline-block mb-2 text-base font-medium">{{$label}} @if(isset($required) && $required) <span style="color: red">*</span> @endif</label>
    <input @if(isset($onchange))  onchange="{{$onchange}}" @endif type="file" name="{{$name}}" id="{{$name}}" @if(isset($src) && $src) data-default-file="{{asset($src)}}"  @endif class="dropify form-input border-slate-200 dark:border-zink-500 focus:outline-none
     focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200
     disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" />
    @if(isset($ajax) && $ajax)
        <span id="{{ $name }}-error-message" class="text-red-500 text-sm error-message"></span>
    @else
        @error($name)
        <div class="text-red-600 text-sm font-semibold">{{ $message }}</div>
        @enderror
    @endif
</div>

