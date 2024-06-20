@extends('layouts.master')
@section('title', $blog->title)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-4">{{ $blog->title }}</h1>
        <div class="flex items-center text-gray-600 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4v8m6 8H6a2 2 0 01-2-2V4a2 2 0 012-2h4l2 2h4a2 2 0 012 2v16a2 2 0 01-2 2z" />
            </svg>
            <span>Estimated read time: {{ $readTime }} minutes</span>
        </div>
        <div class="flex items-center text-gray-600 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553A2 2 0 0017.553 3H6.447a2 2 0 00-1.447 3.447L9 10M15 10h6m-6 0l-1.106-1.106M5 21h14a2 2 0 002-2v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2z" />
            </svg>
            <span>Views: {{ $blog->views }}</span>
        </div>

        @if ($blog->image)
            <img src="{{ asset('storage/' . $blog->image) }}" class="w-full h-auto mb-4 rounded-lg" alt="Blog Image">
        @endif

        <div class="prose max-w-none">
            {{-- {!! nl2br(e($blog->content)) !!} --}}
            {!! ($blog->content) !!}
        </div>
    </div>
</div>
@endsection
