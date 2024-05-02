@extends('layouts.master')
@section('title', 'Add Students')

@push('styles')
@endpush

@section('content')
<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{route('dashboard')}}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="index.html" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Kelola Paket </a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="{{ route('dashboard.tryouts.students.index', $package) }}"
            class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Daftar Siswa</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Tambah Siswa</a>
    </div>
</div>
<div class="header ml-[70px] pr-[70px] w-[940px] flex items-center justify-between mt-10">
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
                    <p class="font-semibold">Jumlah Soal: {{ $totalQuestions }} Soal</p>
                </div>
            </div>

            <div class="flex items-center gap-5">
                <div class="flex gap-[10px] items-center">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{asset('images/icons/profile-2user-outline.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold">{{ count($students) }} Students</p>
                </div>
            </div>
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

<form id="add-question" class="mx-[70px] mt-[30px] flex flex-col gap-5" method="POST"
    action="{{ route('dashboard.tryouts.students.store', $package) }}">
    @csrf
    <h2 class="font-bold text-2xl">Add New Student</h2>
    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Email Address</p>
        <div
            class="flex items-center w-[500px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus-within:border-2 focus-within:border-[#0A090B]">
            <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/sms.svg') }}" class="h-full w-full object-contain" alt="icon">
            </div>
            <input type="text"
                class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none"
                placeholder="Write student email address" name="email">
        </div>
    </div>
    <button type="submit"
        class="w-[500px] h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center">Add
        Student</button>
</form>
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
