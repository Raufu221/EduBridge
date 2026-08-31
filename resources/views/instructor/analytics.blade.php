@extends('layouts.instructor')

@section('title', 'Student Success Analytics')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Dashboard Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Student Engagement</h1>
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-1 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                    Growth, completion, and student satisfaction.
                </p>
            </div>
            <div class="bg-white px-3 py-1.5 rounded-xl shadow-sm border border-gray-100 shrink-0">
                <span class="text-[9px] font-black text-teal-600 uppercase tracking-tighter">Engagement Hub</span>
            </div>
        </div>

        <!-- 1. KPI Ribbon (4 Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Students -->
            <div class="relative bg-white p-5 rounded-2xl shadow-sm border border-gray-50 overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-2.5 bg-teal-100 text-teal-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total Students</p>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">{{ number_format($totalStudents) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Active Courses -->
            <div class="relative bg-white p-5 rounded-2xl shadow-sm border border-gray-50 overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-2.5 bg-indigo-100 text-indigo-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Active Courses</p>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">{{ $activeCourses }}</h3>
                    </div>
                </div>
            </div>

            <!-- Reputation -->
            <div class="relative bg-white p-5 rounded-2xl shadow-sm border border-gray-50 overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-2.5 bg-amber-100 text-amber-600 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Avg Reputation</p>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">{{ number_format($avgRating, 1) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Cumulative Completion Rate -->
            <div class="relative bg-white p-5 rounded-2xl shadow-sm border border-gray-50 overflow-hidden group hover:shadow-md transition-all duration-300">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-2">
                        <div class="p-2.5 bg-emerald-100 text-emerald-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Global Completion</p>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">{{ $completionRate }}%</h3>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Middle Row: Enrollment Line & Category Doughnut -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Line Chart: Enrollments -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex flex-col hover:shadow-md transition">
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Enrollment Over Time</h3>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-6">Monthly new student joins</p>
                <div class="h-[220px] flex-1">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>

            <!-- Doughnut Chart: Category Market Share -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 flex flex-col items-center hover:shadow-md transition">
                <div class="w-full">
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">Niche Distribution</h3>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-6">Enrollment share by category</p>
                </div>
                <div class="h-[180px] w-full relative mb-6">
                    <canvas id="nicheChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-xl font-black text-gray-900 leading-none">{{ $nicheBreakdown->count() }}</span>
                        <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest">Niches</span>
                    </div>
                </div>
                <!-- Custom Legend -->
                <div class="w-full grid grid-cols-2 gap-3">
                    @php $colors = ['#2DD4BF', '#6366F1', '#F43F5E', '#F59E0B', '#3B82F6']; @endphp
                    @foreach($nicheBreakdown as $index => $niche)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $colors[$index % count($colors)] }}"></div>
                                <span class="text-[9px] font-bold text-gray-500 truncate w-24">{{ $niche->name }}</span>
                            </div>
                            <span class="text-[9px] font-black text-gray-900">{{ $totalEnrollmentsCount > 0 ? round(($niche->count / $totalEnrollmentsCount) * 100) : 0 }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 3. Bottom Row: Course Completion Rates (Horizontal Bar) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition">
            <h3 class="text-lg font-black text-gray-900 tracking-tight">Course Completion Rates</h3>
            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-6">Success benchmarking per course</p>
            <div class="h-[250px]">
                <canvas id="completionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Enrollment Line Chart
    const enrolCtx = document.getElementById('enrollmentChart').getContext('2d');
    const gradTeal = enrolCtx.createLinearGradient(0, 0, 0, 300);
    gradTeal.addColorStop(0, 'rgba(45, 212, 191, 0.2)');
    gradTeal.addColorStop(1, 'rgba(45, 212, 191, 0)');

    new Chart(enrolCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'New Students',
                data: {!! json_encode($enrollmentsData) !!},
                borderColor: '#2DD4BF',
                backgroundColor: gradTeal,
                fill: true,
                tension: 0.4,
                borderWidth: 4,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#2DD4BF',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#F8F9FA' }, ticks: { font: { weight: 'bold' } } },
                x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
            }
        }
    });

    // 2. Niche Doughnut
    const nicheCtx = document.getElementById('nicheChart').getContext('2d');
    new Chart(nicheCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($nicheBreakdown->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($nicheBreakdown->pluck('count')) !!},
                backgroundColor: ['#2DD4BF', '#6366F1', '#F43F5E', '#F59E0B', '#3B82F6'],
                borderWidth: 0,
                cutout: '80%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // 3. Horizontal Completion Bar
    const complCtx = document.getElementById('completionChart').getContext('2d');
    new Chart(complCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($courseCompletionData->pluck('course.title')->map(fn($t) => Str::limit($t, 20))) !!},
            datasets: [{
                label: 'Completion Rate %',
                data: {!! json_encode($courseCompletionData->pluck('rate')) !!},
                backgroundColor: '#6366F1',
                borderRadius: 12,
                barThickness: 25
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { max: 100, grid: { borderDash: [5,5] }, ticks: { font: { weight: 'bold' }, callback: (v) => v + '%' } },
                y: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
            }
        }
    });
});
</script>
@endsection
