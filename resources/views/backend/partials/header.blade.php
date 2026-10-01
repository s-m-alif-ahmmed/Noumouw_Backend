<header id="page-topbar"
    class="rtl:md:right-vertical-menu group-data-[sidebar-size=md]:ltr:md:left-vertical-menu-md group-data-[sidebar-size=md]:rtl:md:right-vertical-menu-md group-data-[sidebar-size=sm]:ltr:md:left-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:md:right-vertical-menu-sm group-data-[layout=horizontal]:ltr:left-0 group-data-[layout=horizontal]:rtl:right-0 fixed right-0 z-[1000] left-0 print:hidden group-data-[navbar=bordered]:m-4 group-data-[navbar=bordered]:[&.is-sticky]:mt-0 transition-all ease-linear duration-300 group-data-[navbar=hidden]:hidden group-data-[navbar=scroll]:absolute group/topbar group-data-[layout=horizontal]:z-[1004] ltr:md:left-vertical-menu ">
    <style>
        .remove-caret::after {
            display: none !important;
        }
    </style>
    <div class="layout-width">
        <div class="flex items-center px-6 mx-auto bg-white/80 backdrop-blur-xl border-b border-slate-100 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] h-[76px] dark:bg-zink-800/80 dark:border-zink-700 transition-all duration-300 z-50 sticky top-0">
            <div class="flex items-center w-full group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl navbar-header group-data-[layout=horizontal]:ltr:xl:pr-3 group-data-[layout=horizontal]:rtl:xl:pl-3">
                {{-- Sidebar Toggle Button --}}
                <button type="button" id="sidebar-toggle-btn"
                    class="inline-flex items-center justify-center w-10 h-10 text-slate-500 transition-all duration-200 ease-linear bg-slate-50 border border-slate-100 rounded-xl hover:bg-slate-100 hover:text-slate-800 dark:bg-zink-700 dark:text-zink-200 dark:hover:bg-zink-600 dark:border-zink-600 shadow-sm"
                    title="Toggle Sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="w-5 h-5 sidebar-toggle-icon transition-transform duration-300">
                        <line x1="4" x2="20" y1="12" y2="12"></line>
                        <line x1="4" x2="20" y1="6" y2="6"></line>
                        <line x1="4" x2="20" y1="18" y2="18"></line>
                    </svg>
                </button>
                <div class="flex gap-3 ms-auto">
                    {{-- -------------------------------------------Dark and Ligt Mode -------------------------------------------- --}}
                    {{-- <div class="relative flex items-center h-[76px]">
                        <button type="button"
                            class="inline-flex relative justify-center items-center p-0 text-slate-500 transition-all w-10 h-10 duration-200 ease-linear bg-slate-50 border border-slate-100 rounded-xl hover:bg-slate-100 hover:text-slate-800 dark:bg-zink-700 dark:text-zink-200 dark:hover:bg-zink-600 dark:border-zink-600 shadow-sm"
                            id="light-dark-mode">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="m4.93 4.93 1.41 1.41"></path><path d="m17.66 17.66 1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="m6.34 17.66-1.41 1.41"></path><path d="m19.07 4.93-1.41 1.41"></path></svg>
                        </button>
                    </div> --}}

                    {{-- -------------------------------------------Notification Drop down-------------------------------------------- --}}
                    {{-- <div class="relative flex items-center dropdown h-[76px]">
                        <button type="button"
                            class="inline-flex justify-center relative items-center p-0 text-slate-500 transition-all w-10 h-10 duration-200 ease-linear bg-slate-50 border border-slate-100 rounded-xl dropdown-toggle hover:bg-slate-100 hover:text-slate-800 dark:bg-zink-700 dark:text-zink-200 dark:hover:bg-zink-600 dark:border-zink-600 shadow-sm"
                            id="notificationDropdown" data-bs-toggle="dropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path><path d="M4 2C2.8 3.7 2 5.7 2 8"></path><path d="M22 8c0-2.3-.8-4.3-2-6"></path></svg>
                            @if (Auth::check() && Auth::user()->unreadNotifications()->count() > 0)
                                <span class="absolute top-0 right-0 flex w-2.5 h-2.5" id="notification-indicator">
                                    <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-rose-400"></span>
                                    <span class="relative inline-flex w-2.5 h-2.5 rounded-full bg-rose-500 border border-white"></span>
                                </span>
                            @endif
                        </button>
                        <div class="absolute z-50 ltr:text-left rtl:text-right bg-white rounded-md shadow-md !top-4 dropdown-menu min-w-[20rem] lg:min-w-[26rem] dark:bg-zink-600 hidden"
                            aria-labelledby="notificationDropdown" data-popper-placement="bottom-end"
                            style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-0.5px, 54px, 0px);">
                            <div class="p-4">
                                <div class="flex justify-between">
                                    <h6 class="mb-4 text-16">Notifications <span
                                            class="inline-flex items-center justify-center w-5 h-5 ml-1 text-[11px] font-medium border rounded-full text-white bg-orange-500 border-orange-500"
                                            id="notification-count">{{Auth::check() &&  Auth::user()->notifications()->count() }}</span>
                                    </h6>
                                    <div class="">
                                        <button onclick="markAllRead()" class="text-sky-600 hover:underline ">Mark all
                                            read</button>
                                    </div>
                                </div>
                            </div>

                            <div data-simplebar="init" class="max-h-[350px] simplebar-scrollable-y">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                aria-label="scrollable content"
                                                style="height: auto; overflow: hidden scroll;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    <div class="flex flex-col gap-1" id="notification-list">
                                                        @if(Auth::check())
                                                            @forelse(Auth::user()->notifications as $notification)
                                                                @if ($notification->data['status'] == 'new_blog')
                                                                    <div id="notification_{{ $notification->id }}"
                                                                         class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zinc-500 follower">
                                                                        <div
                                                                            class="w-10 h-10 rounded-md shrink-0 bg-slate-100">
                                                                            <img src="{{ asset($notification->data['thumbnail']) }}"
                                                                                 alt="" class="rounded-md">
                                                                        </div>
                                                                        <div class="grow">
                                                                            <h6
                                                                                class="mb-1 notification-title font-medium {{ $notification->read() ? 'text-gray-400' : '' }}">
                                                                                {{ $notification->data['message'] ?? 'performed an action' }}
                                                                            </h6>
                                                                            <p class="text-sm text-gray-400 my-3">
                                                                                {{ strlen($notification->data['title']) > 50 ? substr($notification->data['title'], 50) . '....' : $notification->data['title'] }}
                                                                            </p>
                                                                            <p
                                                                                class="mb-0 text-sm text-slate-500 dark:text-zinc-300">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor" stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     data-lucide="clock"
                                                                                     class="lucide lucide-clock inline-block w-3.5 h-3.5 mr-1">
                                                                                    <circle cx="12" cy="12"
                                                                                            r="10"></circle>
                                                                                    <polyline points="12 6 12 12 16 14">
                                                                                    </polyline>
                                                                                </svg>
                                                                                <span
                                                                                    class="align-middle">{{ $notification->created_at->diffForHumans() }}</span>
                                                                            </p>
                                                                        </div>
                                                                        <div
                                                                            class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zinc-300">
                                                                            <button
                                                                                onclick="deleteNotification('{{ $notification->id }}')"
                                                                                class="text-red-500">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="none" viewBox="0 0 24 24"
                                                                                     stroke-width="1.5"
                                                                                     stroke="currentColor" class="size-5">
                                                                                    <path stroke-linecap="round"
                                                                                          stroke-linejoin="round"
                                                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @elseif($notification->data['status'] == 'order')
                                                                    <div id="notification_{{ $notification->id }}"
                                                                         class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zinc-500 follower">
                                                                        <div
                                                                            class="w-10 h-10 rounded-md shrink-0 bg-slate-100 flex justify-center items-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="24" height="24"
                                                                                 viewBox="0 0 24 24" fill="none"
                                                                                 class="fill-pink-700">
                                                                                <path opacity="0.4"
                                                                                      d="M19.24 5.58006H18.84L15.46 2.20006C15.19 1.93006 14.75 1.93006 14.47 2.20006C14.2 2.47006 14.2 2.91006 14.47 3.19006L16.86 5.58006H7.14L9.53 3.19006C9.8 2.92006 9.8 2.48006 9.53 2.20006C9.26 1.93006 8.82 1.93006 8.54 2.20006L5.17 5.58006H4.77C3.87 5.58006 2 5.58006 2 8.14006C2 9.11006 2.2 9.75006 2.62 10.1701C2.86 10.4201 3.15 10.5501 3.46 10.6201C3.75 10.6901 4.06 10.7001 4.36 10.7001H19.64C19.95 10.7001 20.24 10.6801 20.52 10.6201C21.36 10.4201 22 9.82006 22 8.14006C22 5.58006 20.13 5.58006 19.24 5.58006Z" />
                                                                                <path
                                                                                    d="M19.66 10.7H4.35996C4.06996 10.7 3.74996 10.69 3.45996 10.61L4.71996 18.3C5.00996 20.02 5.75996 22 9.08996 22H14.7C18.07 22 18.67 20.31 19.03 18.42L20.54 10.61C20.26 10.68 19.96 10.7 19.66 10.7ZM14.88 15.05L11.63 18.05C11.49 18.18 11.3 18.25 11.12 18.25C10.93 18.25 10.74 18.18 10.59 18.03L9.08996 16.53C8.79996 16.24 8.79996 15.76 9.08996 15.47C9.37996 15.18 9.85996 15.18 10.15 15.47L11.14 16.46L13.86 13.95C14.16 13.67 14.64 13.69 14.92 13.99C15.21 14.3 15.19 14.77 14.88 15.05Z" />
                                                                            </svg>
                                                                        </div>
                                                                        <div class="grow">
                                                                            <h6
                                                                                class="mb-1 notification-title font-medium {{ $notification->read() ? 'text-gray-400' : '' }}">
                                                                                {{ $notification->data['message'] ?? 'performed an action' }}
                                                                            </h6>
                                                                            <p
                                                                                class="mb-0 text-sm text-slate-500 dark:text-zinc-300">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor" stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     data-lucide="clock"
                                                                                     class="lucide lucide-clock inline-block w-3.5 h-3.5 mr-1">
                                                                                    <circle cx="12" cy="12"
                                                                                            r="10"></circle>
                                                                                    <polyline points="12 6 12 12 16 14">
                                                                                    </polyline>
                                                                                </svg>
                                                                                <span
                                                                                    class="align-middle">{{ $notification->created_at->diffForHumans() }}</span>
                                                                            </p>
                                                                        </div>
                                                                        <div
                                                                            class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zinc-300">
                                                                            <button
                                                                                onclick="deleteNotification('{{ $notification->id }}')"
                                                                                class="text-red-500">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="none" viewBox="0 0 24 24"
                                                                                     stroke-width="1.5"
                                                                                     stroke="currentColor" class="size-5">
                                                                                    <path stroke-linecap="round"
                                                                                          stroke-linejoin="round"
                                                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @elseif($notification->data['status'] == 'new_user')
                                                                    <div id="notification_{{ $notification->id }}"
                                                                         class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zinc-500 follower">
                                                                        <div
                                                                            class="w-10 h-10 rounded-md shrink-0 bg-slate-100 flex justify-center items-center">
                                                                            <svg width="24" height="24"
                                                                                 viewBox="0 0 24 24" fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M18 13C17.06 13 16.19 13.33 15.5 13.88C14.58 14.61 14 15.74 14 17C14 17.75 14.21 18.46 14.58 19.06C15.27 20.22 16.54 21 18 21C19.01 21 19.93 20.63 20.63 20C20.94 19.74 21.21 19.42 21.42 19.06C21.79 18.46 22 17.75 22 17C22 14.79 20.21 13 18 13ZM20.07 16.57L17.94 18.54C17.8 18.67 17.61 18.74 17.43 18.74C17.24 18.74 17.05 18.67 16.9 18.52L15.91 17.53C15.62 17.24 15.62 16.76 15.91 16.47C16.2 16.18 16.68 16.18 16.97 16.47L17.45 16.95L19.05 15.47C19.35 15.19 19.83 15.21 20.11 15.51C20.39 15.81 20.37 16.28 20.07 16.57Z"
                                                                                    fill="#292D32" />
                                                                                <path opacity="0.4"
                                                                                      d="M21.0899 21.5C21.0899 21.78 20.8699 22 20.5899 22H3.40991C3.12991 22 2.90991 21.78 2.90991 21.5C2.90991 17.36 6.98991 14 11.9999 14C13.0299 14 14.0299 14.14 14.9499 14.41C14.3599 15.11 13.9999 16.02 13.9999 17C13.9999 17.75 14.2099 18.46 14.5799 19.06C14.7799 19.4 15.0399 19.71 15.3399 19.97C16.0399 20.61 16.9699 21 17.9999 21C19.1199 21 20.1299 20.54 20.8499 19.8C21.0099 20.34 21.0899 20.91 21.0899 21.5Z"
                                                                                      fill="#292D32" />
                                                                                <path
                                                                                    d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                                                                    fill="#292D32" />
                                                                            </svg>
                                                                        </div>
                                                                        <div class="grow">
                                                                            <h6
                                                                                class="mb-1 notification-title font-medium {{ $notification->read() ? 'text-gray-400' : '' }}">
                                                                                {{ $notification->data['message'] ?? 'performed an action' }}
                                                                            </h6>
                                                                            <p
                                                                                class="mb-0 text-sm text-slate-500 dark:text-zinc-300">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor" stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     data-lucide="clock"
                                                                                     class="lucide lucide-clock inline-block w-3.5 h-3.5 mr-1">
                                                                                    <circle cx="12" cy="12"
                                                                                            r="10"></circle>
                                                                                    <polyline points="12 6 12 12 16 14">
                                                                                    </polyline>
                                                                                </svg>
                                                                                <span
                                                                                    class="align-middle">{{ $notification->created_at->diffForHumans() }}</span>
                                                                            </p>
                                                                        </div>
                                                                        <div
                                                                            class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zinc-300">
                                                                            <button
                                                                                onclick="deleteNotification('{{ $notification->id }}')"
                                                                                class="text-red-500">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     fill="none" viewBox="0 0 24 24"
                                                                                     stroke-width="1.5"
                                                                                     stroke="currentColor" class="size-5">
                                                                                    <path stroke-linecap="round"
                                                                                          stroke-linejoin="round"
                                                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @empty
                                                                <div class="mx-auto mt-40" id="notification-empty">
                                                                    <p class="text-gray-600 mx-auto">Empty</p>
                                                                </div>
                                                            @endforelse
                                                        @else
                                                            <div class="mx-auto mt-40" id="notification-empty">
                                                                <p class="text-gray-600 mx-auto">Empty</p>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: 416px; height: 455px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                    <div class="simplebar-scrollbar"
                                        style="height: 269px; display: block; transform: translate3d(0px, 0px, 0px);">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- ----------------------------------------------Settings Drop down---------------------------------------------- --}}
                    {{-- <div class="relative items-center hidden h-[76px] md:flex">
                        <button data-drawer-target="customizerButton" type="button"
                            class="inline-flex justify-center items-center p-0 text-slate-500 transition-all w-10 h-10 duration-200 ease-linear bg-slate-50 border border-slate-100 rounded-xl hover:bg-slate-100 hover:text-slate-800 dark:bg-zink-700 dark:text-zink-200 dark:hover:bg-zink-600 dark:border-zink-600 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div> --}}

                    {{-- ----------------------------------------------Profile Drop down---------------------------------------------- --}}
                    <div class="relative flex items-center dropdown h-[76px]">
                        <button type="button"
                        class="relative inline-block p-1 border-2 border-slate-100 dark:border-zink-700 transition-all duration-200 ease-linear rounded-full dropdown-toggle remove-caret hover:border-blue-500 shadow-sm"
                        id="dropdownMenuButton" data-bs-toggle="dropdown">
                        <div class="relative">
                            <img src="{{Auth::check() && auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('/backend/images/user.png') }}"
                                alt="{{Auth::check() && auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                            <!-- Active Status Button -->
                            <div class="absolute bottom-0 p-1 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>
                    </button>

                        <div class="absolute z-50 hidden p-4 ltr:text-left rtl:text-right bg-white rounded-md shadow-md !top-4 dropdown-menu min-w-[14rem] dark:bg-zink-600"
                            aria-labelledby="dropdownMenuButton">
                            <h6 class="mb-2 text-sm font-normal text-slate-500 dark:text-zink-300">Welcome to
                                {{ config('app.name') }}</h6>
                            <a href="{{ route('setting.profile.index') }}" class="flex items-center gap-3 mb-3">
                                <div class="relative inline-block shrink-0">
                                    <div class="rounded-full">
                                        <img src="{{Auth::check() && auth()->user()->avatar ? asset(auth()->user()->avatar) : asset('/backend/images/user.png') }}"
                                            alt="{{Auth::check() && auth()->user()->name }}" class="w-12 h-12 rounded-full">
                                    </div>
                                </div>
                                <h6 class="mb-1 text-15">{{ auth()->user()->name }}</h6>
                                {{-- <div>
                                    <p class="text-slate-500 dark:text-zink-300">{{auth()->user()->role}}</p>
                                </div> --}}
                            </a>
                            <ul>
                                <li>
                                    <a class="block ltr:pr-4 rtl:pl-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:text-custom-500 focus:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:focus:text-custom-500"
                                        href="{{ route('setting.profile.index') }}"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            data-lucide="user-2"
                                            class="lucide lucide-user-2 inline-block size-4 ltr:mr-2 rtl:ml-2">
                                            <circle cx="12" cy="8" r="5"></circle>
                                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                                        </svg> Profile</a>
                                </li>
                                <li class="pt-2 mt-2 border-t border-slate-200 dark:border-zink-500">
                                    <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="block ltr:pr-4 rtl:pl-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:text-custom-500 focus:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:focus:text-custom-500"
                                        href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            data-lucide="log-out"
                                            class="lucide lucide-log-out inline-block size-4 ltr:mr-2 rtl:ml-2">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" x2="9" y1="12" y2="12">
                                            </line>
                                        </svg> Sign Out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>
<link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>
<script>
    function deleteNotification(id) {
        $.ajax({
            type: "DELETE",
            url: "{{ route('notifications.delete', ':id') }}".replace(':id', id),
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            success: function(resp) {
                flasher.success(resp.message);
                $("#notification_" + id).remove()
                let notificationCount = parseInt($("#notification-count").text());
                if (notificationCount > 0) {
                    $("#notification-count").text(notificationCount - 1);
                }
            },
            error: function(error) {
                flasher.error(error.responseJSON.message);
            }
        });
    }

    function markAllRead() {
        $.ajax({
            type: "POST",
            url: "{{ route('notifications.mark-all-read') }}",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            success: function(resp) {
                flasher.success(resp.message);
                $('.notification-title').addClass('text-gray-400');
                $("#notification-indicator").remove()
            },
            error: function(error) {
                flasher.error(error.responseJSON.message);
            }
        });
    }
</script>
