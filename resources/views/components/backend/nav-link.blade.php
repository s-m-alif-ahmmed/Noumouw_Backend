<li class="relative group-data-[layout=horizontal]:shrink-0 group/sm">
    <a href="{{$link}}"
       class="menu-link sidebar-nav-link @if (isset($active) && $active) active @endif relative flex items-center px-4 py-3 mx-3 my-1 transition-all duration-300 ease-in-out rounded-xl text-slate-600 hover:text-blue-600"
       title="{{$title}}">
        <span class="nav-icon min-w-[2rem] inline-block text-xl">
            {{$slot}}
        </span>
        <span class="sidebar-nav-text align-middle font-medium tracking-wide text-sm ml-2" data-key="t-{{strtolower($title)}}">{{$title}}</span>
    </a>
</li>
