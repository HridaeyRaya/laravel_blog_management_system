@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-800">Manage Users</h1>
            <span class="text-sm text-gray-500">Total: {{ $users->count() }}</span>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4">{{ session('error') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $user->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4">
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
        {{ $user->roles->contains('name', \App\Enum\RoleName::Admin->value) ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
        {{ ucfirst($user->roles->first()?->name?->value ?? 'user') }}
    </span>
                        </td>
                        <td class="px-6 py-4">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $user->is_active ? 'Active' : 'Suspended' }}
            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-3 py-1 rounded text-white text-xs font-medium
                    {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}
                    {{ $user->id === auth()->id() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    {{ $user->is_active ? 'Suspend' : 'Unsuspend' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
