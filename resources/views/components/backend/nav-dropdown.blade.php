<li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
    <a href="javascript:void(0)" data-key="{{$dataKey}}"
       class="menu-link sidebar-nav-link dropdown-button @if($active) active show @endif relative flex items-center justify-between px-4 py-3 mx-3 my-1 transition-all duration-300 ease-in-out rounded-xl text-slate-600 hover:text-blue-600 cursor-pointer"
       title="{{$title}}">
        <div class="flex items-center">
            <span class="nav-icon min-w-[2rem] inline-block text-xl">
               {{$icon}}
            </span>
            <span class="sidebar-nav-text align-middle font-medium tracking-wide text-sm ml-2">{{$title}}</span>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-dropdown-arrow w-4 h-4 transition-transform duration-300 ease-in-out arrow-icon"><path d="m6 15 6-6 6 6"/></svg>
    </a>
    <div class="dropdown-content sidebar-dropdown-content @if(!$active) hidden @endif transition-all duration-300 overflow-hidden pl-8">
        <ul class="mt-1 space-y-1 px-4">
           {{$slot}}
        </ul>
    </div>
</li>
