<div class="w-full {{$class ?? ''}}">
    <label for="{{$id ?? $name}}" class="inline-block mb-2 text-base font-medium">{{$label}} @if(isset($required) && $required) <span
            style="color: red">*</span>  @endif</label>
    <textarea name="{{$name}}" id="{{$id ?? $name}}"
              class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error($name) is-invalid @enderror"
              rows="7" placeholder="Enter {{ucfirst($name)}} Here">{{ old($name,$value ?? '') }}</textarea>
    @if(isset($ajax) && $ajax)
        <span id="{{ $name }}-error-message" class="text-red-500 text-sm error-message"></span>
    @else
        @error($name)
           <div class="text-red-600 text-sm font-semibold">{{ $message }}</div>
        @enderror
    @endif

</div>
