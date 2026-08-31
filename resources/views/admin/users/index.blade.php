@extends('layouts.admin')

@section('title', 'Master User Management')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ showAddModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="text-sm text-gray-500 mt-1">Search, promote, and moderate all platform users.</p>
        </div>
        
        <!-- Search & Filter Form & Add User -->
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none w-full sm:w-64 bg-white shadow-sm">
                </div>
                <select name="role" class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white shadow-sm shrink-0" onchange="this.form.submit()">
                    <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Students</option>
                    <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>Instructors</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                </select>
                @if(request()->has('search') || request()->has('role'))
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 shrink-0">Clear</a>
                @endif
            </form>

            <button @click="showAddModal = true" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition shrink-0">
                <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add User
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 mb-6 text-sm text-rose-800 rounded-lg bg-rose-50 border border-rose-200">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Current Role</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Platform Stats</th>
                    <th class="px-6 py-4">Joined Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold border border-indigo-100">{{ substr($user->name, 0, 1) }}</div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                            <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Admin</span>
                        @elseif($user->role === 'instructor')
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Instructor</span>
                        @else
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-[10px] font-bold uppercase tracking-wider">Student</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->status === 'active')
                            <span class="flex items-center gap-1.5 text-emerald-600 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active</span>
                        @elseif($user->status === 'suspended')
                            <span class="flex items-center gap-1.5 text-amber-500 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Suspended</span>
                        @else
                            <span class="flex items-center gap-1.5 text-rose-600 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Banned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role === 'instructor')
                            <div class="text-xs"><span class="font-bold text-gray-800">{{ $user->courses_count }}</span> Published Courses</div>
                        @else
                            <!-- Mocked enrollments until Phase 4 -->
                            <div class="text-xs"><span class="font-bold text-gray-800">{{ rand(0, 5) }}</span> Enrollments</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        @if($user->role !== 'admin')
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                </button>
                                
                                <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50 border border-gray-100 divide-y divide-gray-50 overflow-hidden" x-transition.opacity>
                                    
                                    <a href="#" onclick="alert('Profile Viewer coming soon')" class="block px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition text-left">View Full Profile</a>
                                    
                                    <form action="{{ route('admin.users.promote', $user->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm font-semibold text-indigo-600 hover:bg-indigo-50 transition">
                                            {{ $user->role === 'instructor' ? 'Demote to Student' : 'Promote to Instructor' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm font-semibold text-amber-600 hover:bg-amber-50 transition">
                                            {{ $user->status === 'active' ? 'Suspend Account' : 'Reactivate Account' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Safe delete this user? This will hide them but preserve their financial transaction history.')" class="w-full text-left px-4 py-3 text-sm font-bold text-rose-600 hover:bg-rose-50 transition">
                                            Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <span class="text-[10px] uppercase font-bold text-gray-300">Protected</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $users->links() }}
            </div>
        @endif
        
        @if($users->isEmpty())
            <div class="px-6 py-12 text-center text-gray-500">No users found matching your search.</div>
        @endif
    </div>

    <!-- AlpineJS Modal for Adding User -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showAddModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showAddModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-50 sm:mx-0 sm:h-10 sm:w-10 border border-indigo-100">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">Manually Create User</h3>
                                <p class="text-sm text-gray-500 mb-6 mt-1">Bypass the public funnel to spawn an account.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address</label>
                                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Temporary Password</label>
                                        <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assigned Role</label>
                                        <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none bg-white font-medium text-gray-700">
                                            <option value="student">Student Account</option>
                                            <option value="instructor">Instructor Account</option>
                                            <option value="admin">Platform Administrator</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition">
                            Create User
                        </button>
                        <button type="button" @click="showAddModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
