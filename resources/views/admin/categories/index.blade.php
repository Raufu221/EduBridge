@extends('layouts.admin')

@section('title', 'Category Management')

@section('content')
<div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: The Form -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
            <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-3">
                <div class="bg-indigo-50 p-2 rounded-lg text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <h2 class="text-lg font-black text-gray-900 tracking-tight">Add Category</h2>
            </div>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Category Name</label>
                    <input type="text" name="name" required placeholder="e.g. Graphic Design..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 transition font-bold text-gray-700">
                    @error('name') <p class="text-rose-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Min Price (৳)</label>
                        <input type="number" name="min_price" value="500" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 transition font-black text-gray-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Max Price (৳)</label>
                        <input type="number" name="max_price" value="15000" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 transition font-black text-gray-700">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    Create Category
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: The Table -->
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Active Categories</h1>
            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-black uppercase tracking-widest border border-gray-200">{{ $categories->count() }} Subjects</span>
        </div>

        @if(session('success'))
            <div class="p-4 mb-6 text-[11px] font-bold text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-6 text-[11px] font-bold text-rose-800 rounded-xl bg-rose-50 border border-rose-200">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-100 uppercase text-[9px] font-black tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4 text-center">Courses</th>
                        <th class="px-6 py-4 text-center">Allowed Range</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/30 transition group">
                        <td class="px-6 py-4">
                            <p class="font-black text-gray-900">{{ $category->name }}</p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight mt-0.5">Slug: {{ $category->slug }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($category->courses_count > 0)
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[9px] font-black uppercase tracking-widest border border-indigo-100">{{ $category->courses_count }} Enrollments</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-50 text-gray-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gray-100">Empty</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-[10px] font-black text-gray-800 bg-gray-100 px-2 py-1 rounded-lg border border-gray-200">
                                ৳{{ number_format($category->min_price) }} - ৳{{ number_format($category->max_price) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition">
                                <a href="{{ route('admin.categories.show', $category->id) }}" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition shadow-sm border border-emerald-100" title="View Courses">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>

                                <button type="button" 
                                        onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->min_price }}, {{ $category->max_price }})"
                                        class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition shadow-sm border border-indigo-100" title="Edit Category">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Permanently delete this category?');" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition border border-rose-100" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-bold italic">
                            No categories exist yet. Build your niches using the form!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-indigo-600 px-8 py-8 relative overflow-hidden text-white">
                <div class="relative z-10">
                    <h3 class="text-xl font-black tracking-tight">Edit Category</h3>
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest mt-1">Pricing Ranges & Attributes</p>
                </div>
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-white opacity-10 rounded-full blur-3xl"></div>
            </div>

            <form id="editCategoryForm" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Category Name</label>
                    <input type="text" id="edit_name" name="name" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 font-bold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Min Price (৳)</label>
                        <input type="number" id="edit_min" name="min_price" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 font-black">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Max Price (৳)</label>
                        <input type="number" id="edit_max" name="max_price" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 font-black">
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 py-4 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, name, min, max) {
    const modal = document.getElementById('editCategoryModal');
    const form = document.getElementById('editCategoryForm');
    
    form.action = `/admin/categories/${id}`;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_min').value = min;
    document.getElementById('edit_max').value = max;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editCategoryModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
