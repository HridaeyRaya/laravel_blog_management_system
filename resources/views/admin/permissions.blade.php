@extends('layouts.app')

@section('title', 'Manage Permissions')

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manage User Permissions</h1>
                    <p class="text-sm text-gray-500">Control what user's role can access</p>
                </div>
            </div>
        </div>

        {{-- Role Cards --}}
        @foreach($roles as $role)

            {{-- Skip admin role - admin has full access by design --}}
            @if(($role->name->value ?? $role->name) === 'admin')
                @continue
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                <div class="h-1 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

                <div class="p-6">
                    {{-- Role Header --}}
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center border border-emerald-100">
                            <span class="text-sm font-bold text-emerald-700 capitalize">
                                {{ substr($role->name->value ?? $role->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 capitalize">{{ $role->name->value ?? $role->name }} Role</h2>
                            <p class="text-xs text-gray-400">{{ $role->permissions->count() }} of {{ $permissions->count() }} permissions assigned</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.permissions.update', $role) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                            @foreach($permissions as $permission)
                                <label class="flex items-center space-x-3 p-3 border border-gray-100 rounded-xl hover:border-emerald-200 hover:bg-emerald-50 cursor-pointer transition group">
                                    <input type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission->id }}"
                                           {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 group-hover:text-emerald-700 transition">
                                            {{ $permission->name }}
                                        </p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $permission->route_name }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-50">
                            <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-sm font-medium rounded-xl hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                                Update {{ ucfirst($role->name->value ?? $role->name) }} Permissions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
