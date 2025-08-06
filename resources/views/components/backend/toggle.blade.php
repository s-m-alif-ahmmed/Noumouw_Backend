<div class="w-full">
    <h4 class="inline-block mb-2 text-base font-medium">{{$label}} @if(isset($required) && $required) <span style="color: red">*</span> @endif</h4>
   <div class="">
        <label for="{{$id ?? $name}}"  class="inline-flex items-center cursor-pointer">
            <input  type="checkbox" name="{{ $name }}" class="sr-only peer" id="{{ $id ?? $name }}" name="status" {{ old($name) || (isset($value) && $value) ? 'checked' : '' }}>
            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
        </label>
       @error($name)
          <div class="text-red-600 text-sm font-semibold">{{ $message }}</div>
       @enderror
   </div>
</div>
