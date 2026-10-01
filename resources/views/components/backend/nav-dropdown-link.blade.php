<li>
    <a href="{{ $link }}" 
       class="@if($active) text-blue-600 font-semibold @else text-slate-500 @endif relative flex items-center px-6 py-2.5 text-sm transition-all duration-300 hover:text-blue-600 hover:translate-x-1 group" 
       data-key="t-{{strtolower($title)}}">
        <span class="absolute left-0 w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-blue-400 transition-colors @if($active) bg-blue-500 @endif"></span>
        {{$title}} 
    </a>
</li>
