@extends('backend.app')
@section('title','Dashboard')
@section('title_url')
<a href="{{ route('dashboard') }}">Dashboard</a>
@endsection
@push('styles')
    <link href="{{asset('vendor/flasher/flasher.min.css')}}" rel="stylesheet">
    <style>
        .card {
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .card:nth-child(odd):hover {
        background-color: #f0f8ff;
        transform: translateY(-5px);
    }

    .card:nth-child(even):hover {
        background-color: #ffe4e1;
        transform: translateY(-5px);
    }
    </style>
@endpush
@section('content')

<div class="grid grid-cols-12 2xl:grid-cols-12 gap-x-5">

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto text-slate-800 bg-slate-100 rounded-full size-14 dark:bg-slate-800/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$users}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Total Users</p>
        </div>
    </div>
    <!--end col-->

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto text-purple-500 bg-purple-100 rounded-full size-14 dark:bg-purple-500/20">
                <i data-lucide="package"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$courses}}"></span></h5>
            <p onclick="" class="text-slate-500 dark:text-zink-200">Total Courses</p>
        </div>
    </div>
    <!--end col-->

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto text-cyan-600-500 bg-cyan-100 rounded-full size-14 dark:bg-cyan-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4a4 4 0 110 8 4 4 0 010-8zm8 12.75V19a1 1 0 01-1 1H5a1 1 0 01-1-1v-2.25a4 4 0 012-3.464M16 12h4m-2-2v4"/>
                  </svg>

            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$instructors}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Total Instructor</p>
        </div>
    </div>
    <!--end col-->

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto text-green-500 bg-green-100 rounded-full size-14 dark:bg-green-500/20">
                <i data-lucide="play"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$videos}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Total Videos</p>
        </div>
    </div>
    <!--end col-->

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="activity"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$activities}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Total Activities</p>
        </div>
    </div>
    <!--end col-->

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="podcast"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$podcasts}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Total Podcasts</p>
        </div>
    </div>
    <!--end col-->

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="star"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$evaluations}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Total Evaluations</p>
        </div>
    </div>
    <!--end col-->

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="help-circle"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$questions}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Total Questions</p>
        </div>
    </div>
    <!--end col-->

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="goal"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$subscriptions}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Total Subscription Plans</p>
        </div>
    </div>

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="help-circle"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$support}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Support Tickets</p>
        </div>
    </div>

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="list-todo"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$pendingSupport}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Pending Support Tickets</p>
        </div>
    </div>

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="bug-off"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$resolvedSupport}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Resolved Tickets</p>
        </div>
    </div>

    <div class="col-span-12 card md:col-span-6 lg:col-span-3 2xl:col-span-2">
        <div class="text-center card-body">
            <div class="flex items-center justify-center mx-auto rounded-full size-14 bg-custom-100 text-custom-500 dark:bg-custom-500/20">
                <i data-lucide="check-check"
                   class="h-4 group-data-[sidebar-size=sm]:h-5 group-data-[sidebar-size=sm]:w-5 transition group-hover/menu-link:animate-icons fill-slate-100 group-hover/menu-link:fill-blue-200 group-data-[sidebar=dark]:dark:fill-zink-600 group-data-[layout=horizontal]:dark:fill-zink-600 group-data-[sidebar=dark]:group-hover/menu-link:dark:fill-custom-500/20 group-data-[layout=horizontal]:dark:group-hover/menu-link:fill-custom-500/20 group-data-[sidebar-size=md]:block group-data-[sidebar-size=md]:mx-auto group-data-[sidebar-size=md]:mb-2"></i>
            </div>
            <h5 class="mt-4 mb-2"><span class="counter-value" data-target="{{$closedSupport}}">0</span></h5>
            <p class="text-slate-500 dark:text-zink-200">Closed Tickets</p>
        </div>
    </div>
    <!--end col-->

    {{-- <div class="col-span-12 card 2xl:col-span-12">
        <div class="card-body">
            <div class="grid items-center grid-cols-1 gap-3 mb-5 2xl:grid-cols-12">
                <div class="2xl:col-span-3">
                    <h6 class="text-15">Product Orders</h6>
                </div><!--end col-->
            </div><!--end grid-->
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead class="ltr:text-left rtl:text-right bg-slate-100 text-slate-500 dark:text-zink-200 dark:bg-zink-600">
                        <tr>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">
                                #
                            </th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Order ID</th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Customer Name</th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Order Date</th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Payment Type</th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Payment Status</th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Quantity</th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Total Amount</th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Status</th>
                            <th class="px-3.5 py-2.5 first:pl-5 last:pr-5 font-semibold border-y border-slate-200 dark:border-zink-500">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                1
                            </td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500"><a href="apps-ecommerce-order-overview.html">#456546546456</a></td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">Rasel Ahmed</td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">786</td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">Stripe</td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500"><span class="py-2 px-3 rounded text-white text-sm bg-green-500">paid</span></td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">88</td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">656</td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                <span class="delivery_status px-2.5 py-2 text-sm inline-block font-medium rounded border bg-gray-100 border-gray-200 text-gray-500 dark:bg-gray-500/20 dark:border-gray-500/20">cxv</span>
                            </td>
                            <td class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center mt-5 md:flex-row">

            </div>
        </div>
    </div> --}}
    <!--end col-->
{{--    <div class="col-span-12 card lg:col-span-6 2xl:col-span-3">--}}
{{--        <div class="card-body">--}}
{{--            <h6 class="mb-3">Top Customer</h6>--}}
{{--            <ul class="divide-y divide-slate-200 dark:divide-zink-500">--}}
{{--                @foreach($customers as $customer)--}}
{{--                    <li class="flex items-center gap-3 py-2 first:pt-0 last:pb-0">--}}
{{--                        <div class="w-8 h-8 rounded-full shrink-0 bg-slate-100 dark:bg-zink-600">--}}
{{--                            <img src="{{empty($customer->avatar) ? asset('/backend/images/user.png') : (is_url($customer->avatar) ? $customer->avatar : asset($customer->avatar))}}" alt="{{$customer->name}}" class="w-8 h-8 rounded-full">--}}
{{--                        </div>--}}
{{--                        <div class="grow">--}}
{{--                            <h6 class="font-medium">{{$customer->name}}</h6>--}}
{{--                            <p class="text-slate-500 dark:text-zink-200">{{$customer->email}}</p>--}}
{{--                        </div>--}}
{{--                        <div class="shrink-0">--}}
{{--                            <h6>${{$customer->payments_sum_amount ?? 0.00}}</h6>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    </div><!--end col-->--}}

</div>
<!--end grid-->

{{--<video id="video-player" controls width="640" height="360">--}}
{{--    <source src="https://mickelmcs.softvencefsd.xyz/private-video/course/video/F9kh82WK7G4XcZzN2e5IVWx5Uy3v9OWMJsfucXwR.mp4?v={{time()}}" type="video/mp4">--}}
{{--    Your browser does not support the video tag.--}}
{{--</video>--}}

@endsection

@push('scripts')
    <script src="{{asset('backend/js/datatables/jquery-3.7.0.js')}}"></script>
    <script src="{{asset('vendor/flasher/flasher.min.js')}}"></script>
@endpush
