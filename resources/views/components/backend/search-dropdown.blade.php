

<div class="search-container w-full">
    <label for="{{$id ?? $name}}" class="inline-block mb-2 text-base font-medium">{{$label}} @if(isset($required) && $required) <span style="color: red">*</span> @endif</label>
    <input type="text" id="{{$id ?? $name}}" name="{{$name}}" value="{{ old($name,$value ?? '') }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Start typing...">
    <div id="search_dropdown" class="search_dropdown bg-white divide-y divide-gray-100 rounded-lg shadow w-full max-h-[150px] dark:bg-gray-700"></div>
    @error($name)
       <div class="text-red-600 text-sm font-semibold">{{ $message }}</div>
    @enderror
</div>
<script>
    const suggestions = @json($data);
    const searchInput = document.getElementById("{{$id ?? $name}}");
    const dropdown = document.getElementById('search_dropdown');
    searchInput.addEventListener('focus', function() {
        dropdown.style.display = 'block';
        showDropdown(suggestions);
    });
    searchInput.addEventListener('blur', function() {
        setTimeout(() => {
            dropdown.style.display = 'none';
        }, 200);
    });
    dropdown.addEventListener('mouseover', function() {
        dropdown.style.display = 'block';
    });
    searchInput.addEventListener('input', function() {
        const inputValue = this.value.toLowerCase();
        dropdown.innerHTML = '';
        const filteredSuggestions = suggestions.filter(item =>
            item.toLowerCase().includes(inputValue)
        );
        showDropdown(filteredSuggestions);
    });
    dropdown.addEventListener('click', function(e) {
        if (e.target.classList.contains('search_dropdown-item')) {
            searchInput.value = e.target.textContent;
            dropdown.style.display = 'none';
        }
    });
    function showDropdown(items) {
        dropdown.innerHTML = '';
        if (items.length > 0) {
            dropdown.style.display = 'block';
            items.forEach(item => {
                const div = document.createElement('div');
                div.classList.add('search_dropdown-item');
                div.textContent = item;
                dropdown.appendChild(div);
            });
        } else {
            dropdown.style.display = 'none';
        }
    }
</script>
