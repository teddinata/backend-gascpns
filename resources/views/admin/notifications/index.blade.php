@extends('layouts.master')
@section('title', 'Notifications')

@section('content')
<div class="container mx-4 px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Notifications</h1>
        <a href="{{ route('dashboard.notifications.create') }}" class="h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full
        font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D]">Add New Course</a>
    </div>

    @if ($errors->any())
    <div class="flex flex-col gap-5 px-5 mt-5">
        <div class="flex items center gap-2 bg-[#FEE2E2] p-4 w-full rounded-[10px]">
            <img src="{{ asset('images/icons/closed.png') }}" alt="icon" class="w-6 h-6">
            <p class="font-medium text-red-500">{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    @if (session('success'))
    <div class="flex flex-col gap-5 px-5 mt-5">
        <div class="flex items center gap-2 bg-[#D5EFFE] p-4 w-full rounded-[10px]">
            <img src="{{ asset('images/icons/shield-check.png') }}" alt="icon" class="w-6 h-6">
            <p class="font-medium text-green-500">{{ session('success') }}</p>
            <button onclick="this.parentElement.remove()" class="text-[#3DB475]">&times;</button>
        </div>
    </div>
    @endif

    <div class="mb-4">
        <form method="GET" action="{{ route('dashboard.notifications.index') }}">
            <label for="per_page" class="block text-sm font-medium text-gray-700">Items per page</label>
            <select name="per_page" id="per_page" class="mt-1 block w-32">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
            </select>
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Apply</button>
        </form>
    </div>

    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($notifications as $index => $notification)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $notification->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($notification->icon)
                            <img src="{{ asset('storage/' . $notification->icon) }}" class="w-10 h-10 object-cover" alt="Icon">
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $notification->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $notification->message }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $notification->link }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('dashboard.notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
