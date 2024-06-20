@extends('layouts.master')
@section('title', 'Create Notification')

@section('content')
<div class="container mx-4 px-4 sm:px-6 lg:px-8 py-12">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('dashboard.notifications.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full" required>
        </div>
        <div class="mb-4">
            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
            <textarea name="message" id="message" class="mt-1 block w-full" required></textarea>
        </div>
        <div class="mb-4">
            <label for="link" class="block text-sm font-medium text-gray-700">Link (Optional)</label>
            <input type="text" name="link" id="link" class="mt-1 block w-full">
        </div>
        <div class="mb-4">
            <label for="icon" class="block text-sm font-medium text-gray-700">Icon (Optional)</label>
            <input type="file" name="icon" id="icon" class="mt-1 block w-full">
        </div>
        <div>
            <button type="submit" class="h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center">Create Notification</button>
        </div>
    </form>
</div>
@endsection
