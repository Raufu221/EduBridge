@extends('layouts.admin')

@section('title', 'Revenue Analytics')

@section('header_assets')
    <!-- Chart.js for beautiful analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Revenue Analytics</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Visual trends and course-by-course profit breakdown.</p>
        </div>
        
        <div class="flex gap-3">
            <div class="bg-[#5A4BFF] text-white px-6 py-3 rounded-2xl shadow-sm">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Platform Lifetime</p>
                <p class="text-xl font-black">৳{{ number_format($courseBreakdown->sum('platform_earnings'), 0) }}</p>
            </div>
            <div class="bg-indigo-50 text-indigo-700 px-6 py-3 rounded-2xl border border-indigo-100">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Instructor Payouts</p>
                <p class="text-xl font-black">৳{{ number_format($courseBreakdown->sum('instructor_earnings'), 0) }}</p>
            </div>
        </div>
    </div>

    <!-- 1. VISUAL ANALYTICS: MONTHLY REVENUE -->
    <div class="grid grid-cols-1 mb-8">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
            <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Monthly Platform Revenue (৳)
            </h3>
            <div class="h-[400px] w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 2. REVENUE BREAKDOWN BY COURSE -->
    <div class="mb-8">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-black text-gray-900">Revenue Breakdown by Course</h3>
                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Completed Sales Only</span>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Course Name</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Sales</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total Gross</th>
                        <th class="px-8 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest text-right">Platform Fee (30%)</th>
                        <th class="px-8 py-4 text-[10px] font-black text-emerald-400 uppercase tracking-widest text-right">Instructor (70%)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($courseBreakdown as $row)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-8 py-4">
                                <p class="font-bold text-gray-900 text-sm">{{ $row->course->title }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $row->course->instructor->name }}</p>
                            </td>
                            <td class="px-8 py-4 text-center font-bold text-gray-600 text-sm">
                                {{ $row->sales_count }}
                            </td>
                            <td class="px-8 py-4 text-right font-black text-gray-900 text-sm">
                                ৳{{ number_format($row->total_gross, 0) }}
                            </td>
                            <td class="px-8 py-4 text-right font-black text-indigo-600 text-sm bg-indigo-50/30">
                                ৳{{ number_format($row->platform_earnings, 0) }}
                            </td>
                            <td class="px-8 py-4 text-right font-black text-emerald-600 text-sm">
                                ৳{{ number_format($row->instructor_earnings, 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-gray-400 italic">No sales data available yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- REVENUE CHART INITIALIZATION -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            // Prepare Data from Backend
            const labels = {!! json_encode($monthlyRevenue->pluck('month_label')) !!};
            const dataValues = {!! json_encode($monthlyRevenue->pluck('total')) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Platform Revenue (৳)',
                        data: dataValues,
                        backgroundColor: 'rgba(90, 75, 255, 0.7)',
                        borderColor: '#5A4BFF',
                        borderWidth: 2,
                        borderRadius: 12,
                        barThickness: 45,
                        hoverBackgroundColor: '#5A4BFF',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1E293B',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return '৳' + new Intl.NumberFormat().format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: true, color: '#F1F5F9' },
                            ticks: {
                                font: { size: 11, weight: 'bold' },
                                color: '#64748B',
                                callback: function(value) {
                                    return '৳' + value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 11, weight: 'bold' },
                                color: '#64748B'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
