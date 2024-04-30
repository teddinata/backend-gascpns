@extends('layouts.master')
@section('title', 'Daftar Siswa')

@push('styles')
@endpush

@section('content')

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{route('dashboard')}}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="index.html" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Courses</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Course Students</a>
    </div>
</div>
<div class="header ml-[70px] pr-[70px] w-[940px] flex items-center justify-between mt-10">
    <div class="flex gap-6 items-center">
        <div class="w-[150px] h-[150px] flex shrink-0 relative overflow-hidden">
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
                        <img src="{{asset('images/icons/calendar-add.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold">{{ $course->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{asset('images/icons/profile-2user-outline.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold">{{ count($students) }} Students</p>
                </div>
            </div>
        </div>
    </div>
    <div class="relative">
        <a href="{{ route('dashboard.courses.course_students.create', $course) }}" class="h-[52px] p-[14px_30px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D]">Add Student</a>
    </div>
</div>
<div id="course-test" class="mx-[70px] w-[870px] mt-[30px]">
    <h2 class="font-bold text-2xl">Students</h2>
    <div class="flex flex-col gap-5 mt-2">
        @forelse ($students as $student)
        <div class="student-card w-full flex items-center justify-between p-4 border border-[#EEEEEE] rounded-[20px]">
            <div class="flex gap-4 items-center">
                <div class="w-[50px] h-[50px] flex shrink-0 rounded-full overflow-hidden">
                    <img src="{{asset('images/photos/default-photo.svg') }}" class="w-full h-full object-cover" alt="photo">
                </div>
                <div class="flex flex-col gap-[2px]">
                    <p class="font-bold text-lg">{{ $student->name }}</p>
                    <p class="text-[#7F8190]">{{ $student->email }}</p>
                </div>
            </div>

            @if($student->status == 'Not Passed')
            <div class="flex items-center gap-[14px]">
                <p class="p-[6px_10px] rounded-[10px] bg-[#FD445E] font-bold text-xs text-white outline-[#FD445E] outline-dashed outline-[2px] outline-offset-[4px] mr-[6px]">Not Passed</p>
            </div>
            @elseif($student->status == 'Passed')
            <div class="flex items-center gap-[14px]">
                <p class="p-[6px_10px] rounded-[10px] bg-[#06BC65] font-bold text-xs text-white outline-[#06BC65] outline-dashed outline-[2px] outline-offset-[4px] mr-[6px]">Passed</p>
            </div>
            @elseif($student->status == 'In Progress')
            <div class="flex items-center gap-[14px]">
                <p class="p-[6px_10px] rounded-[10px] bg-[#F9A826] font-bold text-xs text-white outline-[#F9A826] outline-dashed outline-[2px] outline-offset-[4px] mr-[6px]">In Progress</p>
            </div>
            @elseif ($student->status == 'Not Started')
            <div class="flex items-center gap-[14px]">
                <p class="p-[6px_10px] rounded-[10px] bg-[#7F8190] font-bold text-xs text-white outline-[#7F8190] outline-dashed outline-[2px] outline-offset-[4px] mr-[6px]">Not Started</p>
            </div>
            @endif
        </div>
        @empty
        <div class="flex flex-col items-center justify-center w-full h-[300px] border border-[#EEEEEE] rounded-[14px]">
            <img src="{{ asset('images/icons/empty-folder.svg') }}" alt="empty-state" class="mb-5" style="width: 250px">
            <p class="font-bold text-[#7F8190]">No Students Found</p>
        </div>
        @endforelse

    </div>
</div>
@endsection

@push('scripts')
@endpush

