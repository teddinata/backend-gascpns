@extends('layouts.master')
@section('title', 'Blogs')

@push('styles')
<style>
    .text-ellipsis {
        display: block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

@endpush

@section('content')
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

<div class="container mx-4 px-4 sm:px-6 lg:px-8 py-12">

    <div class="flex flex-col px-5 mt-5">
        <div class="w-full flex justify-between items-center">
            <div class="flex flex-col gap-1">
                <p class="font-extrabold text-[30px] leading-[45px]">Manage Blog</p>
                <p class="text-[#7F8190]">Add, edit, delete, and manage all blog posts</p>
            </div>
            <div class="flex items-center space-x-5"> <!-- Container untuk tombol dan form search -->
                <form class="search flex items-center w-[500px] h-[52px] p-[10px_16px] rounded-full border border-[#EEEEEE]">
                    <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Search by course name, etc" name="search">

                    {{-- <input type="text" id="searchInput" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Search by course name, etc" name="search"> --}}
                    <button type="submit" class="w-8 h-8 flex items-center justify-center">
                        <img src="{{ asset('images/icons/search.svg') }}" alt="icon">
                    </button>
                </form>
                <a href="{{ route('dashboard.blogs.create') }}" class="h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full
                font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D]">Create Blog</a>
            </div>
        </div>
    </div>

    <div class="blog-list-container flex flex-col px-5 mt-[30px] gap-[30px]">
        <div class="transaction-list-header flex flex-nowrap justify-between pb-4 pr-10 border-b border-[#EEEEEE]">
            <div class="flex shrink-0 w-[200px]">
                <p class="text-[#7F8190]">Gambar</p>
            </div>
            <div class="flex justify-center shrink-0 w-[150px]">
                <p class="text-[#7F8190]">Judul</p>
            </div>
            <div class="flex justify-center shrink-0 w-[500px]">
                <p class="text-[#7F8190]">Konten</p>
            </div>
            <div class="flex justify-center shrink-0 w-[100px]">
                <p class="text-[#7F8190]">Views</p>
            </div>
            <div class="flex justify-center shrink-0 w-[100px]">
                <p class="text-[#7F8190]">Created At</p>
            </div>
            <div class="flex justify-center shrink-0 w-[150px]">
                <p class="text-[#7F8190]">Aksi</p>
            </div>
        </div>
        @forelse ($blogs as $blog)
        <div class="list-items flex flex-nowrap justify-between pr-10">
            <div class="flex shrink-0 w-[200px]">
                {{-- show image --}}
                @if ($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" class="w-[150px] h-[150px] object-cover rounded-[14px]" alt="blog-image">
                @else
                <img src="{{ asset('images/icons/empty-folder.svg') }}" class="w-[150px] h-[150px] object-cover rounded-[14px]" alt="empty-folder">
                @endif
            </div>
            <div class="flex shrink-0 w-[150px] items-center justify-center">
                <p class="text-ellipsis" title="{{ strip_tags($blog->title) }}">{{ strip_tags($blog->title) }}</p>
            </div>
            <div class="flex shrink-0 w-[500px] items-center justify-center">
                <p class="text-ellipsis" title="{{ strip_tags($blog->content) }}">{{ strip_tags($blog->content) }}</p>
            </div>
            <div class="flex shrink-0 w-[100px] items-center justify-center">
                <p>{{ $blog->views }}</p>
            </div>
            <div class="flex shrink-0 w-[100px] items-center justify-center">
                <p>{{ $blog->created_at->format('Y-m-d') }}</p>
            </div>
            <div class="flex shrink-0 w-[150px] items-center justify-center">
                {{-- icon eye use fontawesome --}}
                <a href="{{ route('dashboard.blogs.show', $blog) }}" class="text-[#007BFF] font-semibold" data-toggle="tooltip" data-placement="top" title="View Blog">
                    <i class="fas fa-eye"></i>
                </a>
                {{-- edit btn --}}
                <a href="{{ route('dashboard.blogs.edit', $blog) }}" class="text-[#FFC107] font-semibold ml-3" data-toggle="tooltip" data-placement="top" title="Edit Blog {{ $blog->title }}">
                    <i class="fas fa-pen text-[#FFC107]"></i>
                </a>
                {{-- delete btn --}}
                <form action="{{ route('dashboard.blogs.destroy', $blog) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-[#FF0000] font-semibold ml-3" data-toggle="tooltip" data-placement="top" title="Delete Blog">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center w-full h-[300px] border border-[#EEEEEE] rounded-[14px]">
            <img src="{{ asset('images/icons/empty-folder.svg') }}" alt="empty-state" class="mb-5" style="width: 250px">
            <p class="font-bold text-[#7F8190]">Tidak Ada Transaksi</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
