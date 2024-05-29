@extends('layouts.master')
@section('title', 'Edit Question')

@push('styles')
@endpush

@section('content')

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{route('dashboard')}}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="{{ route('dashboard.courses.index') }}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Courses</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Question List</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Edit Question</a>

    </div>
</div>
<div class="header ml-[70px] pr-[70px] w-[940px] flex items-center justify-between mt-10">
    <div class="flex gap-6 items-center">
        <div class="w-[150px] h-[150px] flex shrink-0 relative overflow">
            {{-- <img src="{{ asset('images/thumbnail/Web-Development.png') }}" class="w-full h-full object-contain" alt="icon"> --}}
            <img src="{{ Storage::url($course->cover) }}" class="w-full h-full object-contain" alt="icon">
            @if ($course->category->name == 'TIU')
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]
                absolute bottom-0 transform -translate-x-1/2 left-1/2 text-nowrap">{{ $course->category->full_name }}</p>
            @elseif ($course->category->name == 'TWK')
            <p class="p-[8px_16px] rounded-full bg-[#EAE8FE] font-bold text-sm text-[#2B82FE]
                absolute bottom-0 transform -translate-x-1/2 left-1/2 text-nowrap">{{ $course->category->full_name }}</p>
            @elseif ($course->category->name == 'TKP')
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]
                absolute bottom-0 transform -translate-x-1/2 left-1/2 text-nowrap">{{ $course->category->full_name }}</p>
            @endif
        </div>
        <div class="flex flex-col gap-5">
            <h1 class="font-extrabold text-[30px] leading-[45px]">{{ $course->name }}</h1>
            <div class="flex items-center gap-5">
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{ asset('images/icons/calendar-add.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold">{{ $course->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{ asset('images/icons/profile-2user-outline.svg') }}" alt="icon">
                    </div>
                    {{-- <p class="font-semibold">{{ count($students) }} Students</p> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="relative">
        <a href="#" id="more-button" class="toggle-button w-[46px] h-[46px] flex shrink-0 rounded-full items-center justify-center border border-[#EEEEEE]">
            <img src="{{ asset('images/icons/more.svg') }}" alt="icon">
        </a>
        <div class="dropdown-menu absolute hidden right-0 top-[66px] w-[270px] flex flex-col gap-4 p-5 border border-[#EEEEEE] bg-white rounded-[18px] transition-all duration-300 shadow-[0_10px_16px_0_#0A090B0D]">
            <a href="" class="flex gap-[10px] items-center">
                <div class="w-5 h-5">
                    <img src="{{ asset('images/icons/profile-2user-outline.svg') }}" alt="icon">
                </div>
                <span class="font-semibold text-sm">Add Students</span>
            </a>
            <a href="" class="flex gap-[10px] items-center">
                <div class="w-5 h-5">
                    <img src="{{ asset('images/icons/note-favorite-outline.svg') }}" alt="icon">
                </div>
                <span class="font-semibold text-sm">Edit Course Details</span>
            </a>
            <a href="" class="flex gap-[10px] items-center">
                <div class="w-5 h-5">
                    <img src="{{ asset('images/icons/crown-outline.svg') }}" alt="icon">
                </div>
                <span class="font-semibold text-sm">Upload Certificate</span>
            </a>
            <a href="" class="flex gap-[10px] items-center text-[#FD445E]">
                <div class="w-5 h-5">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.5 4.98332C14.725 4.70832 11.9333 4.56665 9.15 4.56665C7.5 4.56665 5.85 4.64998 4.2 4.81665L2.5 4.98332" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.08325 4.14163L7.26659 3.04996C7.39992 2.25829 7.49992 1.66663 8.90825 1.66663H11.0916C12.4999 1.66663 12.6083 2.29163 12.7333 3.05829L12.9166 4.14163" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15.7084 7.6167L15.1667 16.0084C15.0751 17.3167 15.0001 18.3334 12.6751 18.3334H7.32508C5.00008 18.3334 4.92508 17.3167 4.83341 16.0084L4.29175 7.6167" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.6084 13.75H11.3834" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.91675 10.4166H12.0834" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Delete Course</span>
            </a>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Holy smokes!</strong>
        <span class="block sm:inline">Something seriously bad happened.</span>
        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('dashboard.course_questions.update', $courseQuestion) }}"
    id="add-question" enctype="multipart/form-data" class="mx-[70px] mt-[30px] flex flex-col gap-5">
    @csrf
    @method('PUT')
    <h2 class="font-bold text-2xl">Edit Question</h2>

    <div class="flex gap-5 items-center">
        <input type="file" name="image" id="icon" class="peer hidden" onchange="previewFile()" data-empty="true">
        <div class="relative w-[500px] h-[250px] overflow-hidden peer-data-[empty=true]:border-[3px] peer-data-[empty=true]:border-dashed peer-data-[empty=true]:border-[#EEEEEE]">
            <div class="relative file-preview z-10 w-full h-full">
                <img src="{{ Storage::url($courseQuestion->image) }}" class="thumbnail-icon w-full h-full object-cover"
                    alt="thumbnail">
            </div>
            <span class="absolute top-0 left-0 w-full h-full flex items-center justify-center text-center font-semibold text-sm text-[#7F8190]">
                <i class="fa-solid fa-cloud-arrow-up mr-2" style="font-size: 20px"></i>
                Gambar Soal <br>(Opsional)
            </span>
        </div>

        <button type="button" class="flex shrink-0 p-[8px_20px] h-fit items-center rounded-full bg-[#0A090B] font-semibold text-white" onclick="document.getElementById('icon').click()">
            <i class="fa-solid fa-cloud-arrow-up mr-2" style="font-size: 20px"></i>
            <span> Tambah Gambar untuk Soal</span>
        </button>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Question</p>
        {{-- <div class="flex items-center w-[940px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus-within:border-2 focus-within:border-[#0A090B]">
            <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/note-text.svg') }}" class="h-full w-full object-contain" alt="icon">
            </div>
            <input type="text" value="{{ $courseQuestion->question }}"
            class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none"
            placeholder="Write the question" name="question">
        </div> --}}
        <textarea name="question" id="question" class="w-[940px] h-[100px] p-[14px_16px] rounded-[10px] border border-[#EEEEEE] \
        focus:border-2 focus:border-[#0A090B] outline-none"
        placeholder="Write the question">{{ $courseQuestion->question }}</textarea>
    </div>
    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Answers</p>
        @forelse ($courseQuestion->answers as $i => $answer)
        <div class="flex items-center gap-4">
            <div class="flex items-center w-[940px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus-within:border-2 focus-within:border-[#0A090B]">
                <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/icons/edit.svg') }}" class="h-full w-full object-contain" alt="icon">
                </div>
                <input type="text" class="font-semibold placeholder:text-[#7F8190]
                    placeholder:font-normal w-full outline-none" value="{{ $answer->answer }}"
                    placeholder="Write better answer option" name="answers[]">
            </div>
            <div class="flex items-center w-[200px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus-within:border-2 focus-within:border-[#0A090B]">
                <div class="mr-[14px] w-3 h-6 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/icons/code.svg') }}" class="h-full w-full object-contain" alt="icon">
                </div>
                <input type="number" class="font-semibold placeholder:text-[#7F8190]
                    placeholder:font-normal w-full outline-none"
                    placeholder="Score (0-5)"
                    name="score[]"
                    min="0"
                    max="5"
                    oninput="checkValue(this, {{ $i }})"
                    value="{{ $answer->score }}">
                </div>
            <span id="error{{ $i }}" style="color: red; display: none;">Error: Nilai harus diantara 1-5 dan tidak boleh sama!</span>
        </div>
        @empty
        @endforelse
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Explanation</p>
        <div class="flex items-center w-[1140px] p-[14px_16px] rounded-md border border-[#EEEEEE]">
            <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/note-text.svg') }}" class="h-full w-full object-contain" alt="icon">
            </div>
            <textarea id="explanation" name="explanation" rows="10" class="flex-1 p-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg
            border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
            dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
            focus:outline-none focus:ring focus:ring-opacity-75 focus:ring-offset-2 focus:ring-offset-gray-100"
            placeholder="Write the explanation">{{ $courseQuestion->explanation }}</textarea>
        </div>
    </div>

    <button type="submit"
    class="w-[500px] h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300
    hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center">Update Question</button>
</form>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/cidud93cpqy1w1o737hl6g4tfjsh3xnqtfiz548zskmzga0w/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>


<script>
    tinymce.init({
      selector: '#question',
      plugins: '    emoticons wordcount',
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

<script>
    tinymce.init({
        selector: '#explanation',
        // plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
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

     function checkValue(input, index) {
        var value = input.value;
        var error = document.getElementById('error' + index);
        var allScores = document.getElementsByName('score[]');
        var duplicate = false;

        for (var i = 0; i < allScores.length; i++) {
            if (i != index && allScores[i].value == value && value != 0) {
                duplicate = true;
                break;
            }
        }

        if (value < 0 || value > 5 || duplicate) {
            error.style.display = 'block';
            input.style.borderColor = 'red';
            input.value  = '';
        } else {
            error.style.display = 'none';
        }
    }
</script>
@endpush
