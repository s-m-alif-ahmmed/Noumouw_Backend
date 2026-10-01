<div id="{{ $id }}-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[9999]"></div>
<div id="{{ $id }}" class="fixed w-full sm:w-full md:w-[60rem] lg:w-[60rem] p-2 transition-all duration-300 ease-in-out left-2/4 z-[99991] -translate-x-2/4 top-20 hidden {{ $class ?? '' }}" style="{{ $style ?? '' }}">
    <div class="w-full bg-white shadow rounded-md dark:bg-zink-600 {{ isset($class) ? 'md:w-full' : '' }}">
        <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
            <h5 class="text-16" id="{{ $id }}Label">{{ $title }}</h5>
            <button data-modal-close="{{ $id }}" id="{{ $id }}Close" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>
        <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>
