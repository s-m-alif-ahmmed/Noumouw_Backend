@extends('backend.app')
@section('title', 'Dashboard')

@push('styles')
    <style>
        :root {
            --premium-blue: #3b82f6;
            --premium-indigo: #6366f1;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        .premium-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .premium-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: var(--premium-blue);
        }

        .premium-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--premium-blue), var(--premium-indigo));
            opacity: 0;
            transition: opacity 0.4s;
        }

        .premium-card:hover::before {
            opacity: 1;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .premium-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        .chart-container {
            min-height: 350px;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            padding: 24px;
        }

        .premium-gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white">Welcome back, <span
                        class="premium-gradient-text">Admin</span> 👋</h1>
                <p class="text-slate-500 mt-1">Here's what's happening with your platform today.</p>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Users -->
            <div class="premium-card p-6">
                <div class="icon-box bg-blue-100 text-blue-600 dark:bg-blue-500/20">
                    <i data-lucide="users" class="size-7"></i>
                </div>
                <div class="stat-value counter-value" data-target="{{ $users }}">0</div>
                <p class="text-slate-500 font-medium">Total Active Students</p>
                {{-- <div class="mt-4 flex items-center gap-2 text-green-500 text-sm font-semibold">
                    <i data-lucide="trending-up" class="size-4"></i>
                    <span>+12.5% vs last month</span>
                </div> --}}
            </div>

            <!-- Courses -->
            <div class="premium-card p-6">
                <div class="icon-box bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20">
                    <i data-lucide="book-open" class="size-7"></i>
                </div>
                <div class="stat-value counter-value" data-target="{{ $courses }}">0</div>
                <p class="text-slate-500 font-medium">Published Courses</p>
                {{-- <div class="mt-4 flex items-center gap-2 text-indigo-500 text-sm font-semibold">
                    <i data-lucide="plus-circle" class="size-4"></i>
                    <span>3 new this week</span>
                </div> --}}
            </div>

            <!-- Instructors -->
            <div class="premium-card p-6">
                <div class="icon-box bg-purple-100 text-purple-600 dark:bg-purple-500/20">
                    <i data-lucide="graduation-cap" class="size-7"></i>
                </div>
                <div class="stat-value counter-value" data-target="{{ $instructors }}">0</div>
                <p class="text-slate-500 font-medium">Expert Instructors</p>
                {{-- <div class="mt-4 flex items-center gap-2 text-slate-400 text-sm">
                    <span>98% positive rating</span>
                </div> --}}
            </div>

            <!-- Subscriptions -->
            <div class="premium-card p-6">
                <div class="icon-box bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20">
                    <i data-lucide="credit-card" class="size-7"></i>
                </div>
                <div class="stat-value counter-value" data-target="{{ $subscriptions }}">0</div>
                <p class="text-slate-500 font-medium">Total Revenue (USD)</p>
                {{-- <div class="mt-4 flex items-center gap-2 text-emerald-500 text-sm font-semibold">
                    <i data-lucide="arrow-up-right" class="size-4"></i>
                    <span>High demand month</span>
                </div> --}}
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
            <div class="lg:col-span-8">
                <div class="chart-container">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Enrollment Analytics</h3>
                        <select id="enrollment-range" class="bg-slate-50 border-none rounded-lg text-sm px-3 py-1 outline-none">
                            <option value="7">Last 7 Days</option>
                            <option value="30">Last 30 Days</option>
                        </select>
                    </div>
                    <div id="enrollment-chart" class="w-full"></div>
                </div>
            </div>
            <div class="lg:col-span-4">
                <div class="chart-container">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-8 text-center">Content Distribution</h3>
                    <div id="content-donut-chart" class="w-full"></div>
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2">
                                <div class="size-3 rounded-full bg-blue-500"></div> Videos
                            </span>
                            <span class="font-bold">{{ $videos }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2">
                                <div class="size-3 rounded-full bg-indigo-500"></div> Podcasts
                            </span>
                            <span class="font-bold">{{ $podcasts }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2">
                                <div class="size-3 rounded-full bg-emerald-500"></div> Activities
                            </span>
                            <span class="font-bold">{{ $activities }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets & Support -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="premium-card p-6 border-l-4 border-l-yellow-400">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-2xl bg-yellow-100 text-yellow-600 flex items-center justify-center">
                        <i data-lucide="clock" class="size-6"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold">{{ $pendingSupport }}</h4>
                        <p class="text-slate-500 text-sm">Pending Tickets</p>
                    </div>
                </div>
            </div>
            <div class="premium-card p-6 border-l-4 border-l-green-400">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">
                        <i data-lucide="check-circle" class="size-6"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold">{{ $resolvedSupport }}</h4>
                        <p class="text-slate-500 text-sm">Resolved Support</p>
                    </div>
                </div>
            </div>
            <div class="premium-card p-6 border-l-4 border-l-slate-400">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i data-lucide="message-square" class="size-6"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold">{{ $support }}</h4>
                        <p class="text-slate-500 text-sm">Total Inquiries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enrollment Data from Server
            var enrollmentData7 = {
                days: {!! json_encode($enrollmentDays7) !!},
                counts: {!! json_encode($enrollmentCounts7) !!}
            };
            var enrollmentData30 = {
                days: {!! json_encode($enrollmentDays30) !!},
                counts: {!! json_encode($enrollmentCounts30) !!}
            };

            function getEnrollmentOptions(data) {
                return {
                    series: [{
                        name: 'Enrollments',
                        data: data.counts
                    }],
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'Inter, sans-serif'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    colors: ['#3b82f6'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.45,
                            opacityTo: 0.05,
                            stops: [20, 100, 100, 100]
                        }
                    },
                    xaxis: {
                        categories: data.days,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4
                    }
                };
            }

            var enrollmentChart = new ApexCharts(document.querySelector("#enrollment-chart"), getEnrollmentOptions(enrollmentData7));
            enrollmentChart.render();

            // Handle dropdown change to switch between 7-day and 30-day views
            document.getElementById('enrollment-range').addEventListener('change', function() {
                var data = this.value === '30' ? enrollmentData30 : enrollmentData7;
                enrollmentChart.updateOptions(getEnrollmentOptions(data));
            });

            // Content Donut Chart
            var donutOptions = {
                series: [{{ $videos }}, {{ $podcasts }}, {{ $activities }}],
                chart: {
                    type: 'donut',
                    height: 280
                },
                labels: ['Videos', 'Podcasts', 'Activities'],
                colors: ['#3b82f6', '#6366f1', '#10b981'],
                legend: {
                    show: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Items',
                                    formatter: () => {{ $videos + $podcasts + $activities }}
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                }
            };
            var donutChart = new ApexCharts(document.querySelector("#content-donut-chart"), donutOptions);
            donutChart.render();
        });
    </script>
@endpush
