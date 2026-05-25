@extends('layouts.app')

@section('title', 'Manage Permissions')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Manage Role Permissions</h1>

        @foreach($roles as $role)
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 capitalize">
                    {{ $role->name }} Role
                </h2>

                <form method="POST" action="{{ route('admin.permissions.update', $role) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        @foreach($permissions as $permission)
                            <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $permission->id }}"
                                       {{ $role->permissions->contains('id' ,$permission->id) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $permission->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $permission->route_name }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        Update {{ $role->name }} Permissions
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
