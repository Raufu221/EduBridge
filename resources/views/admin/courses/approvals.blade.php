@extends('layouts.admin')

@section('title', 'Course Approvals')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pending Courses</h1>
            <p class="text-sm text-gray-500 mt-1">Review and approve courses submitted by your instructors.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Course details</th>
                    <th class="px-6 py-4">Instructor</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4 text-center">Capacity</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4 text-right">Decision</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pendingCourses as $course)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @if($course->cover_image)
                                <img src="{{ asset('storage/' . $course->cover_image) }}" class="w-16 h-12 rounded object-cover border border-gray-200">
                            @else
                                <div class="w-16 h-12 rounded bg-indigo-50 flex items-center justify-center text-indigo-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-gray-900">{{ $course->title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Submitted {{ $course->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $course->instructor->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4"><span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">{{ $course->category->name ?? 'Category' }}</span></td>
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $course->price > 0 ? '$' . $course->price : 'Free' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($course->max_students)
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-bold border border-gray-200">{{ $course->max_students }} Peers</span>
                        @else
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-md text-xs font-bold border border-emerald-200">Unlimited</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <!-- Preview Action -->
                        <a href="{{ route('admin.course.preview', $course->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-semibold hover:bg-indigo-100 transition border border-indigo-200 shadow-sm">Preview Course</a>
                        
                        <!-- Approve Action -->
                        <form action="{{ route('admin.courses.approve', $course->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('APPROVE: Are you sure you want to make this course live to the public?');" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600 transition shadow-sm">
                                Approve
                            </button>
                        </form>

                        <!-- Reject Action -->
                        <button type="button" 
                                onclick="openRejectModal('{{ $course->id }}', '{{ addslashes($course->title) }}', '{{ addslashes($course->instructor->name ?? 'Unknown') }}')"
                                class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-100 transition">
                            Reject
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="font-bold text-gray-900">Queue is Clear!</p>
                            <p class="text-sm">There are no courses waiting for review.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRejectModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-rose-600 px-8 py-8 relative overflow-hidden">
                <div class="relative z-10 text-white">
                    <h3 class="text-xl font-black tracking-tight" id="modalCourseTitle">Reject Course</h3>
                    <p class="text-rose-100 text-[10px] font-black uppercase tracking-widest mt-1">Quality Control & Feedback</p>
                </div>
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
            </div>

            <form id="rejectForm" method="POST" class="p-8">
                @csrf
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Instructor</p>
                        <p class="text-sm font-bold text-gray-900" id="modalInstructor">Instructor Name</p>
                    </div>

                    <div>
                        <label for="reason" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Reason for Rejection</label>
                        <textarea id="reason" name="reason" rows="5" required
                                  class="w-full bg-gray-50 border-0 rounded-2xl py-4 px-4 text-gray-900 font-bold focus:ring-2 focus:ring-rose-500 transition placeholder-gray-300"
                                  placeholder="Provide specific details so the instructor knows exactly what to fix (e.g., Audio quality in Module 2 is low, cover image is pixelated...)"></textarea>
                        <p class="mt-2 text-[9px] text-gray-400 font-medium italic">This feedback will be visible at the top of the instructor's Course Builder page.</p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeRejectModal()"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-widest py-4 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-black uppercase tracking-widest py-4 rounded-xl transition shadow-lg shadow-rose-100">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal(id, title, instructor) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    
    // Set form action
    form.action = `/admin/courses/${id}/reject`;
    
    // Set text content
    document.getElementById('modalCourseTitle').innerText = 'Reject: ' + title;
    document.getElementById('modalInstructor').innerText = instructor;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent scroll
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
