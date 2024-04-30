@extends('layouts.master')
@section('title', 'Create Question')

@push('styles')
<style>
    /* Custom Select2 styling */
    .select2-container--classic .select2-selection--single {
        height: 52px;
        border: 1px solid #EEEEEE;
        border-radius: 30px;
        background-image: url('{{ asset('images/icons/arrow-down.svg') }}');
        background-repeat: no-repeat;
        background-position: right center;
        padding-right: 40px; /* Adjust the padding to accommodate the arrow */
    }

    .select2-container--classic .select2-selection--single .select2-selection__arrow b {
        border-color: #0A090B;
    }
    /* Style the dropdown menu */
    .select2-container .select2-dropdown {
        border-radius: 10px;
        border-top: 1px solid #0a0707;

    }

    .select2-container--classic .select2-selection--single .select2-selection__rendered {
        line-height: 52px;
        color: #0A090B;
        padding-left: 14px;
    }

    .select2-container--classic .select2-selection--single .select2-selection__arrow {
        top: 19px;
    }

    /* Custom border for dropdown */
    .select2-container--classic.select2-container--open .select2-dropdown {
        border-top: 1px solid #030303;
    }
</style>
@endpush

@section('content')

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{route('dashboard')}}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="{{ route('dashboard.packages.index') }}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Courses</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Course Details</a>
    </div>
</div>
<div class="header pr-[70px] w-[940px] flex items-center justify-between mt-10">
    <div class="flex gap-6 items-center">
        <div class="w-[150px] h-[150px] flex shrink-0 relative overflow">
            <img src="{{ Storage::url($package->cover_path) }}" class="w-full h-full object-contain" alt="icon">
        </div>
        <div class="flex flex-col gap-5">
            <h1 class="font-extrabold text-[30px] leading-[45px]">{{ $package->name }}</h1>
            <div class="flex items-center gap-5">
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{ asset('images/icons/calendar-add.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold">Tanggal Penjualan: {{ $package->sale_start_at ? \Carbon\Carbon::parse($package->sale_start_at)->format('d M Y H:i') : '-' }} - {{ $package->sale_end_at ? \Carbon\Carbon::parse($package->sale_end_at)->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-5">
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{ asset('images/icons/note-favorite-outline.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold">Periode Try Out: {{ $package->sale_start_at ? \Carbon\Carbon::parse($package->sale_start_at)->format('d M Y H:i') : '-' }} - {{ $package->sale_end_at ? \Carbon\Carbon::parse($package->sale_end_at)->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-5">
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{ asset('images/icons/clock.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold">Durasi Pengerjaan: {{ $package->total_duration }} Menit</p>
                </div>

                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fa-regular fa-file-lines" style="font-size: 20px;"></i>
                    </div>
                    {{-- <p class="font-semibold">Jumlah Soal: {{ $total_tryout_courses }} Soal</p> --}}
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

<form method="POST" action="{{ route('dashboard.package_tryouts.update', $package_tryout) }}" id="add-question" class="mx-[70px] mt-[30px] flex flex-col gap-5">
    @csrf
    @method('PUT')
    <h2 class="font-bold text-2xl">Tambah Soal</h2>
    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Soal</p>
        <div class="peer flex items-center p-[12px_16px] rounded-full w-[700px] border border-[#EEEEEE] transition-all duration-300
            focus-within:border-2 focus-within:border-[#0A090B]">
            <div class="mr-[10px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/bill.svg') }}" class="w-full h-full object-contain" alt="icon">
            </div>
            <div class="w-full relative">
                <select id="course" class="pl-1 pr-[32px] font-semibold focus:outline-none w-full text-[#0A090B] invalid:text-[#7F8190]
                                invalid:font-normal appearance-none bg-[url('{{ asset('images/icons/arrow-down.svg') }}')] bg-no-repeat bg-right"
                                name="course_id" required>
                    <option value="" disabled selected hidden>Choose one of category</option>
                    @forelse ($courses as $course)
                        <option value="{{ $course->id }}" class ="font-semibold">{{ $course->name }}</option>
                    @empty
                        <option value="" class="font-semibold">No course available</option>
                    @endforelse
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-[10px] text-[#7F8190]">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.293 7.293a1 1 0 011.414 0L10 9.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414zM10 2a1 1 0 011 1v6a1 1 0 11-2 0V3a1 1 0 011-1z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M10 18a1 1 0 01-1-1v-6a1 1 0 112 0v6a1 1 0 01-1 1z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

    </div>
    {{-- <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Answers</p>
        <div class="flex items-center gap-4">
            <div class="flex items-center w-[500px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus-within:border-2 focus-within:border-[#0A090B]">
                <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/icons/edit.svg') }}" class="h-full w-full object-contain" alt="icon">
                </div>
                <input type="text" class="font-semibold placeholder:text-[#7F8190]
                    placeholder:font-normal w-full outline-none" placeholder="Write better answer option" name="answers[]">
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
                    max="5">
                </div>
           </div>
    </div> --}}
    <button type="submit"
    class="w-[500px] h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300
    hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center">Save Question</button>
</form>
@endsection

@push('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
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

<script>
    // Inisialisasi Select2 pada elemen <select>
    $(document).ready(function() {
        $('#course').select2({
            placeholder: "Choose one of category",
            allowClear: true,
            width: '100%',
            theme: "custom" // Atau gunakan tema Select2 lainnya
        });
    });
</script>

@endpush
