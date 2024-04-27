@extends('layouts.master')
@section('title', 'Learning Rapport')

@push('styles')

@endpush

@section('content')

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="my-course.html" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">My Courses</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Rapport Details</a>
    </div>
</div>
<div class="header ml-[70px] pr-[70px] w-[940px] flex items-center justify-between mt-10">
    <div class="flex gap-6 items-center">
        <div class="w-[150px] h-[150px] flex shrink-0 relative overflow">
            <img src="{{ Storage::url($course->cover)}}" class="w-full h-full object-contain" alt="icon">
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
            <div class="flex items-center">
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{asset('images/icons/note-text.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold">{{ $correctAnswersCount }} of {{ $totalQuestionsCount }} Correct Answers</p>
                </div>
            </div>
            <div class="flex items-center">
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{asset('images/icons/receipt-text.svg') }}" alt="icon">
                    </div>
                    <p class="font-light">Tanggal Pengerjaan: </p>
                    <p class="font-semibold">{{ $date->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center">
        <p class="p-[16px_20px] rounded-[10px] bg-[#FD445E] font-bold text-lg text-white outline-[#FD445E] outline-dashed outline-[3px] outline-offset-[7px] mr-[10px]">Not Passed</p>
        <!-- <p class="p-[16px_20px] rounded-[10px] bg-[#06BC65] font-bold text-lg text-white outline-[#06BC65] outline-dashed outline-[3px] outline-offset-[7px] mr-[10px]">Passed</p> -->
    </div>
</div>
<div class="result flex flex-col gap-5 mx-[70px] w-[870px] mt-[30px]">

    @forelse($studentAnswers as $answer)
    <div class="question-card w-full flex items-center justify-between p-4 border border-[#EEEEEE] rounded-[20px]">
        <div class="flex flex-col gap-[6px]">
            <p class="text-[#7F8190]">Question</p>
            <p class="font-bold text-xl">{{ $answer->question->question }}</p>
        </div>

        @if($answer->answer == 5)
        <div class="flex items-center gap-[14px]">
            <p>Skor: {{ $answer->answer }}</p>
            <p class="bg-[#06BC65] rounded-full p-[8px_20px] text-white font-semibold text-sm">Benar</p>
        </div>
        @else
        <div class="flex items-center gap-[14px]">
            <p>Skor: {{ $answer->answer }}</p>
            <p class="bg-[#FD445E] rounded-full p-[8px_20px] text-white font-semibold text-sm">Salah</p>
        </div>
        @endif
    </div>
    @empty
    <div class="flex items-center justify-center w-full h-[200px] bg-[#F7F8FA] rounded-[20px]">
        <p class="text-[#7F8190]">No data available</p>
    </div>
    @endforelse
</div>
<div class="options flex items-center mx-[70px] gap-5 mt-[30px]">
    <a href="" class="w-fit h-[52px] p-[14px_20px] bg-[#0A090B] rounded-full font-semibold text-white transition-all duration-300 text-center">Request Retake</a>
    <a href="" class="w-fit h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center">Contact Teacher</a>
</div>
@endsection

@push('scripts')

@endpush
