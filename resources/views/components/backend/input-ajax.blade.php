<div class="form-group">
    <label for="{{  $id ?? $name }}" class="inline-block mb-2 text-base font-medium {{$class ?? ''}}">{{ $label }} @if(isset($required) && $required) <span style="color: red">*</span>@endif</label>
    <input type="text" name="{{ $name }}" id="{{ $id ?? $name }}" value="{{ old($name,$value ?? '') }}"
           class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500"
           placeholder="{{ $placeholder ?? '' }}">
    <!-- Error Message -->
    <span id="{{ $name }}-error-message" class="text-red-500 text-sm error-message"></span>
</div>
