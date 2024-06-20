@extends('layouts.master')
@section('title', 'Create Blog')

@section('content')
@if ($errors->any())
<div class="flex flex-col gap-5 px-5 mt-5">
    <div class="flex items-center gap-2 bg-[#FEE2E2] p-4 w-full rounded-[10px]">
        <img src="{{ asset('images/icons/closed.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-red-500">{{ $errors->first() }}</p>
    </div>
</div>
@endif

@if (session('success'))
<div class="flex flex-col gap-5 px-5 mt-5">
    <div class="flex items-center gap-2 bg-[#D5EFFE] p-4 w-full rounded-[10px]">
        <img src="{{ asset('images/icons/shield-check.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-green-500">{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="text-[#3DB475]">&times;</button>
    </div>
</div>
@endif

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{route('dashboard')}}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="{{ route('dashboard.blogs.index') }}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Blog</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Create Blog</a>
    </div>
</div>
<div class="header flex flex-col gap-1 px-5 mt-5">
    <h1 class="font-extrabold text-[30px] leading-[45px]">Paket Soal Baru</h1>
    <p class="text-[#7F8190]">Buat paket soal baru untuk memulai tryout yang baru</p>
</div>

<form method="POST" action="{{ route('dashboard.blogs.store') }}" enctype="multipart/form-data" class="flex flex-col gap-[30px] w-[800px] mx-[70px] mt-10">
    @csrf
    <div class="flex gap-5 items-center mb-4">
        <input type="file" name="image" id="icon" class="peer hidden" onchange="previewFile()" data-empty="true">
        <div class="relative w-[200px] h-[200px] rounded-full overflow-hidden peer-data-[empty=true]:border-[3px] peer-data-[empty=true]:border-dashed peer-data-[empty=true]:border-[#EEEEEE]">
            <div class="relative file-preview z-10 w-full h-full hidden">
                <img src="" class="thumbnail-icon w-full h-full object-cover" alt="thumbnail">
            </div>
            <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 text-center font-semibold text-sm text-[#7F8190]">Blog Image<br> (Optional)</span>
        </div>
        <button type="button" class="flex shrink-0 p-[8px_20px] h-fit items-center rounded-full bg-[#0A090B] font-semibold text-white" onclick="document.getElementById('icon').click()">
            Add Image
        </button>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Title</p>
        <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/note-favorite-outline.svg') }}" class="w-full h-full object-contain" alt="icon">
            </div>
            <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Judul" name="title" required>
        </div>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Content</p>
        <textarea id="content-blog" name="content" class="h-[150px] p-[14px_16px] rounded-lg border border-[#EEEEEE] focus-within:border-2 focus-within:border-[#0A090B] font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none"></textarea>
    </div>

    <div class="mt-4">
        <button type="submit" class="h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center">Create Blog</button>
    </div>
</form>

<script>
    function previewFile() {
        var preview = document.querySelector('.file-preview');
        var fileInput = document.querySelector('input[type=file]');

        if (fileInput.files.length > 0) {
            var reader = new FileReader();
            var file = fileInput.files[0]; // Get the first file from the input

            reader.onloadend = function () {
                var img = preview.querySelector('.thumbnail-icon'); // Get the thumbnail image element
                img.src = reader.result; // Update src attribute with the uploaded file
                preview.classList.remove('hidden'); // Remove the 'hidden' class to display the preview
            }

            reader.readAsDataURL(file);
            fileInput.setAttribute('data-empty', 'false');
        } else {
            preview.classList.add('hidden'); // Hide preview if no file selected
            fileInput.setAttribute('data-empty', 'true');
        }
    }
</script>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/cidud93cpqy1w1o737hl6g4tfjsh3xnqtfiz548zskmzga0w/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content-blog',
        plugins: 'emoticons wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
    });
</script>
@endpush

@endsection
