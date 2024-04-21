@extends('layouts.master')
@section('title', 'My Tryout')

@push('styles')
@endpush

@section('content')
<div class="flex flex-col px-5 mt-5">
    <div class="w-full flex justify-between items-center">
        <div class="flex flex-col gap-1">
            <p class="font-extrabold text-[30px] leading-[45px]">My Courses</p>
            <p class="text-[#7F8190]">Finish all given tests to grow</p>
        </div>
    </div>
</div>
@if ($errors->any())
    <div class="flex flex-col gap-5 px-[70px] mt-[30px]">
        @foreach ($errors->all() as $error)
            <div class="flex items center gap-2 bg-[#FEE2E2] p-4 w-[500px] rounded-[10px]">
                <img src="{{ asset('images/icons/closed.png') }}" alt="icon" class="w-6 h-6">
                <p class="font-medium text-red-500">{{ $error }}</p>
            </div>
        @endforeach
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

<div class="course-list-container flex flex-col px-5 mt-[30px] gap-[30px]">
    <div class="course-list-header flex flex-nowrap justify-between pb-4 pr-10 border-b border-[#EEEEEE]">
        <div class="flex shrink-0 w-[300px]">
            <p class="text-[#7F8190]">Course</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Date Created</p>
        </div>
        <div class="flex justify-center shrink-0 w-[170px]">
            <p class="text-[#7F8190]">Category</p>
        </div>
        <div class="flex justify-center shrink-0 w-[120px]">
            <p class="text-[#7F8190]">Action</p>
        </div>
    </div>
    @forelse ($myCourses as $course)
    <div class="list-items flex flex-nowrap justify-between pr-10">
        <div class="flex shrink-0 w-[300px]">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 flex shrink-0 overflow-hidden rounded-full">
                    <img src="{{ Storage::url($course->cover) }}" class="object-cover" alt="thumbnail">
                </div>
                <div class="flex flex-col gap-[2px]">
                    <p class="font-bold text-lg">{{ $course->name }}</p>
                    <p class="text-[#7F8190]">Beginners</p>
                </div>
            </div>
        </div>
        <div class="flex shrink-0 w-[150px] items-center justify-center">
            <p class="font-semibold">{{ $course->created_at->format('d M Y') }}</p>
        </div>
        @if ($course->category->name == 'TIU')
        <div class="flex shrink-0 w-[170px] items-center justify-center">
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]">{{ $course->category->name }}</p>
        </div>
        @elseif ($course->category->name == 'TKP')
        <div class="flex shrink-0 w-[170px] items-center justify-center">
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]">{{ $course->category->name }}</p>
        </div>
        @elseif ($course->category->name == 'TWK')
        <div class="flex shrink-0 w-[170px] items-center justify-center">
            <p class="p-[8px_16px] rounded-full bg-[#EAE8FE] font-bold text-sm text-[#6436F1]">{{ $course->category->name }}</p>
        </div>
        @endif
        <div class="flex shrink-0 w-[120px] items-center">
            @if($course->nextQuestionId !== null)
            <a href="{{ route('dashboard.learning.course', ['course' => $course->id, 'question' => $course->nextQuestionId]) }}"
                class="w-full h-[41px] p-[10px_20px] bg-[#6436F1] rounded-full font-bold text-sm text-white
                transition-all duration-300 hover:shadow-[0_4px_15px_0_#6436F14D] text-center">Start Test</a>
            @else
            <a href="{{ route('dashboard.learning.rapport.course', ['course' => $course->id, 'question' => $course->nextQuestionId]) }}"
                class="w-full h-[41px] p-[10px_20px] bg-indigo-950 rounded-full font-bold text-sm text-white
                transition-all duration-300 hover:shadow-[0_4px_15px_0_#6436F14D] text-center">Raport</a>
            @endif
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center gap-5">
        <img src="{{ asset('images/illustration/empty-course.svg') }}" alt="empty-course">
        <p class="font-bold text-lg text-[#7F8190]">No Course Found</p>
        <p class="text-[#7F8190] text-center">You haven't joined any course yet. Let's join and start learning!</p>
        <a href="{{ route('student.courses.index') }}" class="w-[200px] h-[41px] bg-[#2B82FE] rounded-full
            font-bold text-sm text-white flex items-center justify-center transition-all duration-300
            hover:shadow-[0_4px_15px_0_#2B82FE4D]">
            Explore Courses
        </a>
    </div>
    @endforelse
</div>
<div class="flex text-[#7F8190] gap-4 items-center mt-[37px] px-5">
    Show data {{ $myCourses->firstItem() }} to {{ $myCourses->lastItem() }} of {{ $myCourses->total() }} total data
</div>
<div id="pagination" class="flex gap-4 items-center mt-[30px] px-5">
    @if ($myCourses->onFirstPage())
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]"><<</button>
    @else
        <a href="{{ $myCourses->previousPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]"><<</a>
    @endif

    @for ($i = 1; $i <= $myCourses->lastPage(); $i++)
        <a href="{{ $myCourses->url($i) }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190] {{ $myCourses->currentPage() == $i ? 'bg-[#0A090B] text-white' : '' }}">{{ $i }}</a>
    @endfor

    @if ($myCourses->hasMorePages())
        <a href="{{ $myCourses->nextPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">>></a>
    @else
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]">>></button>
    @endif
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $errors->first() }}',
        });
    @endif

</script>
@endpush
