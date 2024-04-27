@extends('layouts.master')
@section('title', 'Daftar Siswa')

@push('styles')
@endpush

@section('content')

@if ($errors->any())
<div class="flex flex-col gap-5 px-5 mt-5">
    <div class="flex items center gap-2 bg-[#FEE2E2] p-4 w-[500px] rounded-[10px]">
        <img src="{{ asset('images/icons/closed.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-red-500">{{ $errors->first() }}</p>
    </div>
</div>
@endif

@if (session('success'))
<div class="flex flex-col gap-5 px-5 mt-5">
    <div class="flex items center gap-2 bg-[#D5EFFE] p-4 w-[500px] rounded-[10px]">
        <img src="{{ asset('images/icons/shield-check.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-green-500">{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="text-[#3DB475]">&times;</button>
    </div>
</div>
@endif

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="index.html" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Students</a>
    </div>
</div>

<div class="flex flex-col px-5 mt-5">
    <div class="w-full flex justify-between items-center">
        <div class="flex flex-col gap-1">
            <p class="font-extrabold text-[30px] leading-[45px]">Manage Students</p>
            <p class="text-[#7F8190]">Manage and secure your students data</p>
        </div>
        <div class="flex items-center space-x-5"> <!-- Container untuk tombol dan form search -->
            <form class="search flex items-center w-[500px] h-[52px] p-[10px_16px] rounded-full border border-[#EEEEEE]">
                <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none"
                placeholder="Search by name, etc" name="search">

                {{-- <input type="text" id="searchInput" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Search by course name, etc" name="search"> --}}
                <button type="submit" class="w-8 h-8 flex items-center justify-center">
                    <img src="{{ asset('images/icons/search.svg') }}" alt="icon">
                </button>
            </form>
            {{-- <a href="{{ route('dashboard.courses.create') }}" class="h-[52px] p-[14px_20px] bg-[#6436F1] rounded-full
                font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#6436F14D]">Add New Student</a> --}}
        </div>
    </div>
</div>

<div class="course-list-container flex flex-col px-5 mt-[30px] gap-[30px]">
    <div class="course-list-header flex flex-nowrap justify-between pb-4 pr-10 border-b border-[#EEEEEE]">
        <div class="flex shrink-0 w-[300px]">
            <p class="text-[#7F8190]">Name  (Username)</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Member Joined</p>
        </div>
        <div class="flex justify-center shrink-0 w-[350px]">
            <p class="text-[#7F8190]">Phone Number</p>
        </div>
        <div class="flex justify-center shrink-0 w-[120px]">
            <p class="text-[#7F8190]">Email</p>
        </div>
        <div class="flex justify-center shrink-0 w-[120px]">
            <p class="text-[#7F8190]">Action</p>
        </div>
    </div>
    @forelse ($students as $student)
    <div class="list-items flex flex-nowrap justify-between pr-10">
        <div class="flex shrink-0 w-[300px]">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded-full">
                    @if ($student->avatar)
                        <img src="{{ Storage::url($student->avatar) }}" class="object-cover" alt="thumbnail">
                    @else
                        <img src="http://ui-avatars.com/api/?name={{$student->name}}" class="object-cover" alt="thumbnail">
                    @endif
                </div>
                <div class="flex flex-col gap-[2px]">
                    <p class="font-bold text-lg">{{ $student->name }}</p>
                    <p class="text-[#7F8190] italic text-sm">{{ $student->username ?? 'Belum ada username' }}</p>
                </div>
            </div>
        </div>
        <div class="flex shrink-0 w-[150px] items-center justify-center">
            <p class="font-semibold">{{ $student->created_at->format('d M Y H:i') }}</p>
        </div>
        <div class="flex shrink-0 w-[350px] items-center justify-center">
            <p class="font-semibold">{{ $student->phone_number ?? '-' }}</p>
        </div>
        <div class="flex shrink-0 w-[120px] items-center">
            <p class="font-semibold">{{ $student->email }}</p>
        </div>


        <div class="flex shrink-0 w-[120px] items-center">
            <div class="relative h-[41px]">
                <div class="menu-dropdown w-[120px] max-h-[41px] overflow-hidden absolute top-0 p-[10px_16px] bg-white flex flex-col gap-3 border border-[#EEEEEE] transition-all duration-300 hover:shadow-[0_10px_16px_0_#0A090B0D] rounded-[18px]">
                    <button onclick="toggleMaxHeight(this)" class="flex items-center justify-between font-bold text-sm w-full">
                        menu
                        <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="icon">
                    </button>
                    <a href="{{ route('dashboard.students.show', $student) }}" class="flex items-center justify-between font-bold text-sm w-full">
                        Lihat Detail
                    </a>
                    <a href="{{ route('dashboard.students.edit', $student) }}" class="flex items-center justify-between font-bold text-sm w-full">
                        Edit
                    </a>
                    <form action="{{ route('dashboard.students.destroy', $student->id) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="flex items-center justify-between font-bold text-sm w-full text-[#FD445E]"
                            onclick="confirmDelete(event, {{ $student->id }})">
                            Hapus
                        </button>
                    </form>
                    {{-- <a href="{{ route('dashboard.students.destroy', $student->id) }}"  class="flex items-center justify-between
                        font-bold text-sm w-full text-[#FD445E]"
                        data-confirm-delete="true">Hapus Data</a> --}}
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center w-full h-[200px] border border-[#EEEEEE] rounded-[14px]">
        <img src="{{ asset('images/illustration/empty-state-course.svg') }}" alt="empty-state" class="mb-5">
        <p class="font-bold text-[#7F8190]">No Student Found</p>
    </div>
    @endforelse
</div>

<div class="flex text-[#7F8190] gap-4 items-center mt-[37px] px-5">
    Show data {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} total data
</div>
<div id="pagination" class="flex gap-4 items-center mt-[30px] px-5">
    @if ($students->onFirstPage())
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]"><<</button>
    @else
        <a href="{{ $students->previousPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]"><<</a>
    @endif

    @for ($i = 1; $i <= $students->lastPage(); $i++)
        <a href="{{ $students->url($i) }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190] {{ $students->currentPage() == $i ? 'bg-[#0A090B] text-white' : '' }}">{{ $i }}</a>
    @endfor

    @if ($students->hasMorePages())
        <a href="{{ $students->nextPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">>></a>
    @else
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]">>></button>
    @endif
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
                document.getElementById('deleteForm').action = "{{ route('dashboard.students.index') }}" + '/' + id;
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
