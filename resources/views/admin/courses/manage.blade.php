@extends('layouts.master')
@section('title', 'Manage Course')

@push('styles')
@endpush

@section('content')
<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="{{ route('dashboard.courses.index') }}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Courses</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Course Detail</a>
    </div>
</div>
<div class="header ml-[70px] pr-[70px] w-[940px] flex items-center justify-between mt-10">
    <div class="flex gap-6 items-center">
        <div class="w-[150px] h-[150px] flex shrink-0 relative overflow">
            {{-- <img src="{{ asset('images/thumbnail/Web-Development.png') }}" class="w-full h-full object-contain" alt="icon"> --}}
            <img src="{{ Storage::url($course->cover) }}" class="w-full h-full object-contain" alt="icon">
            @if ($course->category->name == 'TIU')
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]
                absolute bottom-0 transform text-pretty"
                style="font-size: 14px; text-align: center;">{{ $course->category->full_name }}</p>
            @elseif ($course->category->name == 'TWK')
            <p class="p-[8px_16px] rounded-full bg-[#EAE8FE] font-bold text-sm text-[#2B82FE]
                absolute bottom-0 transform text-pretty mt-4"
                style="font-size: 14px; text-align: center;">{{ $course->category->full_name }}</p>
            @elseif ($course->category->name == 'TKP')
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]
                absolute bottom-0 transform text-pretty"
                style="font-size: 14px; text-align: center;">{{ $course->category->full_name }}</p>
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
                    <p class="font-semibold">{{ count($students) }} Students</p>
                </div>
            </div>
        </div>
    </div>
    <div class="relative">
        <a href="#" id="more-button" class="toggle-button w-[46px] h-[46px] flex shrink-0 rounded-full items-center justify-center border border-[#EEEEEE]">
            <img src="{{ asset('images/icons/more.svg') }}" alt="icon">
        </a>
        <div class="dropdown-menu absolute hidden right-0 top-[66px] w-[270px] flex flex-col gap-4 p-5 border border-[#EEEEEE] bg-white rounded-[18px] transition-all duration-300 shadow-[0_10px_16px_0_#0A090B0D]">
            <a href="{{ route('dashboard.courses.course_students.create', $course) }}" class="flex gap-[10px] items-center">
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

@if ($errors->any())
<div class="flex flex-col gap-5 px-[70px] mt-[30px]">
    <div class="flex items center gap-2 bg-[#FEE2E2] p-4 w-[500px] rounded-[10px]">
        <img src="{{ asset('images/icons/closed.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-red-500">{{ $errors->first() }}</p>
    </div>
</div>
@endif

@if (session('success'))
<div class="flex flex-col gap-5 px-[70px] mt-[30px]">
    <div class="flex items center gap-2 bg-[#D5EFFE] p-4 w-[500px] rounded-[10px]">
        <img src="{{ asset('images/icons/shield-check.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-green-500">{{ session('success') }}</p>
    </div>
</div>
@endif

<div id="course-test" class="mx-[70px] w-[870px] mt-[30px]">
    <h2 class="font-bold text-2xl">Course Tests</h2>
    <div class="flex flex-col gap-[30px] mt-2">
        <a href="{{ route('dashboard.course.create.question', $course) }}" class="w-full h-[92px] flex items-center justify-center p-4 border-dashed border-2 border-[#0A090B] rounded-[20px]">
            <div class="flex items-center gap-5">
                <div>
                    <img src="{{ asset('images/icons/note-add.svg') }}" alt="icon">
                </div>
                <p class="font-bold text-xl">New Question</p>
            </div>
        </a>
        @forelse ($questions as $question)
        <div class="question-card w-full flex items-center justify-between p-4 border border-[#EEEEEE] rounded-[20px]">
            <div class="flex flex-col gap-[6px]">
                <p class="text-[#7F8190]">Question</p>
                <p class="font-bold text-xl">{{ $question->question }}</p>
            </div>
            <div class="flex items-center gap-[14px]">
                <a href="{{ route('dashboard.course_questions.edit', $question) }}" class="bg-[#0A090B] p-[14px_30px] rounded-full text-white font-semibold">Edit</a>
                {{-- <form action=""> --}}
                    <a href="{{ route('dashboard.course_questions.destroy', $question) }}"
                        class="w-[52px] h-[52px] flex shrink-0 items-center justify-center rounded-full bg-[#FD445E]"
                        data-confirm-delete="true">
                        <img src="{{ asset('images/icons/trash.svg') }}" alt="icon">
                    </a>
                {{-- </form> --}}

            </div>
        </div>
        @empty
        <div class="w-full h-[92px] flex items-center justify-center p-4 border-dashed border-2 border-[#0A090B] rounded-[20px]">
            <div class="flex items-center gap-5">
                <p class="font-bold text-xl">Kelas ini belum memiliki soal</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuButton = document.getElementById('more-button');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        menuButton.addEventListener('click', function () {
        dropdownMenu.classList.toggle('hidden');
        });

        // Close the dropdown menu when clicking outside of it
        document.addEventListener('click', function (event) {
        const isClickInside = menuButton.contains(event.target) || dropdownMenu.contains(event.target);
        if (!isClickInside) {
            dropdownMenu.classList.add('hidden');
        }
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    @endif

    // if errors any
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $errors->first() }}',
        });
    @endif
</script>
@endpush
