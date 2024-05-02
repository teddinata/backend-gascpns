@extends('layouts.master')
@section('title', 'Daftar Siswa')

@push('styles')
@endpush

@section('content')

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{route('dashboard')}}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="index.html" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Kelola Paket </a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Daftar Siswa</a>
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
                    <p class="font-semibold">{{ count($student_lists) }} Students</p>
                </div>
            </div>
        </div>

    </div>
    <div class="relative">
        <a href="{{ route('dashboard.tryouts.students.create', $package) }}" class="h-[52px] p-[14px_30px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D]">Add Student</a>
    </div>
</div>
<div id="course-test" class="mx-[70px] w-[870px] mt-[30px]">
    <h3 class="font-bold text-xl">Siswa yang terdaftar di Try Out ini</h3>
    <div class="flex flex-col gap-5 mt-2">
        @forelse ($student_lists as $student)
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

            {{-- delete --}}
            {{-- <form action="{{ route('dashboard.tryouts.students.delete', [$package, $student->id]) }}"
                method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="button" class="text-[#7F8190] hover:text-[#FF0000]" onclick="confirmDelete(event, {{ $student->id }})">
                    <i class="fa-solid fa-trash-alt"></i>
                </button>
            </form> --}}
            <div class="flex items-center gap-[14px]">
                <a href="{{ route('dashboard.tryouts.students.delete', [$package, $student->id]) }}"
                    class="flex mr-4 items-center justify-between font-bold text-sm w-full text-[#7F8190] hover:text-[#FF0000]"
                    data-confirm-delete="true"> Hapus data siswa
                    <i class="fa-solid fa-trash-alt ml-2" style="font-size: 20px;"></i>
                </a>
            </div>

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
<script>
    function confirmDelete(event, id) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus data!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').action = "{{ route('dashboard.tryouts.students.index', $package) }}";
                document.getElementById('deleteForm').submit();
            }
        })
    }

    function toggleMaxHeight(button) {
        const menuDropdown = button.parentElement;
        menuDropdown.classList.toggle('max-h-fit');
        menuDropdown.classList.toggle('shadow-[0_10px_16px_0_#0A090B0D]');
        menuDropdown.classList.toggle('z-10');
    }

    document.addEventListener('click', function(event) {
        const menuDropdowns = document.querySelectorAll('.menu-dropdown');
        const clickedInsideDropdown = Array.from(menuDropdowns).some(function(dropdown) {
            return dropdown.contains(event.target);
        });

        if (!clickedInsideDropdown) {
            menuDropdowns.forEach(function(dropdown) {
                dropdown.classList.remove('max-h-fit');
                dropdown.classList.remove('shadow-[0_10px_16px_0_#0A090B0D]');
                dropdown.classList.remove('z-10');
            });
        }
    });

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

