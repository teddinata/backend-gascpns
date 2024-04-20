@extends('layouts.master')
@section('title', 'List Course')

@section('content')

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
        <button onclick="this.parentElement.remove()" class="text-[#3DB475]">&times;</button>
    </div>
</div>
@endif

{{-- section error --}}
<div class="flex flex-col px-5 mt-5">
    <div class="w-full flex justify-between items-center">
        <div class="flex flex-col gap-1">
            <p class="font-extrabold text-[30px] leading-[45px]">Manage Course</p>
            <p class="text-[#7F8190]">Provide high quality for best students</p>
        </div>
        <div class="flex items-center space-x-5"> <!-- Container untuk tombol dan form search -->
            <form class="search flex items-center w-[500px] h-[52px] p-[10px_16px] rounded-full border border-[#EEEEEE]">
                <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Search by course name, etc" name="search">

                {{-- <input type="text" id="searchInput" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Search by course name, etc" name="search"> --}}
                <button type="submit" class="w-8 h-8 flex items-center justify-center">
                    <img src="{{ asset('images/icons/search.svg') }}" alt="icon">
                </button>
            </form>
            <a href="{{ route('dashboard.courses.create') }}" class="h-[52px] p-[14px_20px] bg-[#6436F1] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#6436F14D]">Add New Course</a>
        </div>
    </div>
</div>
<div class="course-list-container flex flex-col px-5 mt-[30px] gap-[30px]">
    <div class="course-list-header flex flex-nowrap justify-between pb-4 pr-10 border-b border-[#EEEEEE]">
        <div class="flex shrink-0 w-[300px]">
            <p class="text-[#7F8190]">Course</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Date Created</p>
        </div>
        <div class="flex justify-center shrink-0 w-[350px]">
            <p class="text-[#7F8190]">Category</p>
        </div>
        {{-- status --}}
        <div class="flex justify-center shrink-0 w-[120px]">
            <p class="text-[#7F8190]">Status</p>
        </div>
        <div class="flex justify-center shrink-0 w-[120px]">
            <p class="text-[#7F8190]">Action</p>
        </div>
    </div>
    @forelse ($courses as $soal)
    <div class="list-items flex flex-nowrap justify-between pr-10">
        <div class="flex shrink-0 w-[300px]">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 flex shrink-0 overflow-hidden rounded-full">
                    <img src="{{ Storage::url($soal->cover) }}" class="object-cover" alt="thumbnail">
                </div>
                <div class="flex flex-col gap-[2px]">
                    <p class="font-bold text-lg">{{ $soal->name }}</p>
                    <p class="text-[#7F8190]">Beginners</p>
                </div>
            </div>
        </div>
        <div class="flex shrink-0 w-[150px] items-center justify-center">
            <p class="font-semibold">{{ $soal->created_at->format('d M Y H:i') }}</p>
        </div>
        @if ($soal->category->name == 'TIU')
        <div class="flex shrink-0 w-[350px] items-center justify-center">
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]">{{ $soal->category->name }}</p>
        </div>
        @elseif ($soal->category->name == 'TKP')
        <div class="flex shrink-0 w-[350px] items-center justify-center">
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]">{{ $soal->category->name }}</p>
        </div>
        @elseif ($soal->category->name == 'TWK')
        <div class="flex shrink-0 w-[350px] items-center justify-center">
            <p class="p-[8px_16px] rounded-full bg-[#EAE8FE] font-bold text-sm text-[#6436F1]">{{ $soal->category->name }}</p>
        </div>
        @endif

        {{-- badge status --}}
        <div class="flex shrink-0 w-[120px] items-center justify-center">
            @if ($soal->status == 1)
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]">{{ $soal->status == 1 ? 'Published' : 'Draft' }}</p>
            @else
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]">{{ $soal->status == 1 ? 'Published' : 'Draft' }}</p>
            @endif
        </div>

        <div class="flex shrink-0 w-[120px] items-center">
            <div class="relative h-[41px]">
                <div class="menu-dropdown w-[120px] max-h-[41px] overflow-hidden absolute top-0 p-[10px_16px] bg-white flex flex-col gap-3 border border-[#EEEEEE] transition-all duration-300 hover:shadow-[0_10px_16px_0_#0A090B0D] rounded-[18px]">
                    <button onclick="toggleMaxHeight(this)" class="flex items-center justify-between font-bold text-sm w-full">
                        menu
                        <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="icon">
                    </button>
                    <a href="{{ route('dashboard.courses.show', $soal) }}" class="flex items-center justify-between font-bold text-sm w-full">
                        Manage
                    </a>
                    <a href="course-students.html" class="flex items-center justify-between font-bold text-sm w-full">
                        Students
                    </a>
                    <a href="{{ route('dashboard.courses.edit', $soal) }}" class="flex items-center justify-between font-bold text-sm w-full">
                        Edit Course
                    </a>
                    {{-- <form action="{{ route('dashboard.courses.destroy', $soal->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" data-confirm-delete="true" class="flex items-center justify-between font-bold text-sm w-full text-[#FD445E]">
                            Delete
                        </button>
                    </form> --}}
                    <a href="{{ route('dashboard.courses.destroy', $soal) }}"  class="flex items-center justify-between font-bold text-sm w-full text-[#FD445E]"
                        data-confirm-delete="true">Delete</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center w-full h-[200px] border border-[#EEEEEE] rounded-[14px]">
        <img src="{{ asset('images/illustration/empty-state-course.svg') }}" alt="empty-state" class="mb-5">
        <p class="font-bold text-[#7F8190]">No Course Found</p>
    </div>
    @endforelse
</div>
{{-- <div id="pagiantion" class="flex gap-4 items-center mt-[37px] px-5">
    <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">1</button>
    <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">2</button>
    <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-white bg-[#0A090B]">3</button>
    <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">4</button>
    <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">5</button>
</div> --}}

{{-- show data 5 of 10 example --}}

<div class="flex text-[#7F8190] gap-4 items-center mt-[37px] px-5">
    Show data {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} total data
</div>
<div id="pagination" class="flex gap-4 items-center mt-[30px] px-5">
    @if ($courses->onFirstPage())
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]"><<</button>
    @else
        <a href="{{ $courses->previousPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]"><<</a>
    @endif

    @for ($i = 1; $i <= $courses->lastPage(); $i++)
        <a href="{{ $courses->url($i) }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190] {{ $courses->currentPage() == $i ? 'bg-[#0A090B] text-white' : '' }}">{{ $i }}</a>
    @endfor

    @if ($courses->hasMorePages())
        <a href="{{ $courses->nextPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">>></a>
    @else
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]">>></button>
    @endif
</div>

@endsection

@push('scripts')
<script>
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

{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#searchInput').on('input', function(){
            var searchText = $(this).val();
            $.ajax({
                url: '{{ route("dashboard.courses.search") }}',
                type: 'GET',
                data: {search: searchText},
                success: function(response){
                    // Handle response dari server, misalnya update daftar hasil pencarian di halaman
                    console.log(response);

                    $('.course-list-container').empty();
                    if(response.length > 0) {
                        response.forEach(function(course){
                        var courseItem = `
                            <div class="list-items flex flex-nowrap justify-between pr-10">
                                <div class="flex shrink-0 w-[300px]">
                                    <div class="flex items center gap-4">
                                        <div class="w-16 h-16 flex shrink-0 overflow-hidden rounded-full">
                                            <img src="${course.cover}" class="object-cover" alt="thumbnail">
                                        </div>
                                        <div class="flex flex-col gap-[2px]">
                                            <p class="font-bold text-lg">${course.name}</p>
                                            <p class="text-[#7F8190]">Beginners</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex shrink-0 w-[150px] items-center justify-center">
                                    <p class="font-semibold">${course.created_at}</p>
                                </div>
                                <div class="flex shrink-0 w-[350px] items-center justify-center">
                                    <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]">${course.category}</p>
                                </div>
                                <div class="flex shrink-0 w-[120px] items-center justify-center">
                                    <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]">${course.status}</p>
                                </div>
                                <div class="flex shrink-0 w-[120px] items-center">
                                    <div class="relative h-[41px]">
                                        <div class="menu-dropdown w-[120px] max-h-[41px] overflow-hidden absolute top-0 p-[10px_16px] bg-white flex flex-col gap-3 border border-[#EEEEEE] transition-all duration-300 hover:shadow-[0_10px_16px_0_#0A090B0D] rounded-[18px]">
                                            <button onclick="toggleMaxHeight(this)" class="flex items-center justify-between font-bold text-sm w-full">
                                                menu
                                                <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="icon">
                                            </button>
                                            <a href="#" class="flex items-center justify-between font-bold text-sm w-full">
                                                Manage
                                            </a>
                                            <a href="course-students.html" class="flex items center justify-between font-bold text-sm w-full">
                                                Students
                                            </a>
                                            <a href="${dashboard.courses.edit}" class="flex items center justify-between font-bold text-sm w-full">
                                                Edit Course
                                            </a>
                                            <a href="${dashboard.courses.destroy}"  class="flex items-center justify-between font-bold text-sm w-full text-[#FD445E]" data-confirm-delete="true">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('.course-list-container').append(courseItem);
                    });
                    updatePagination(response);
                    } else {
                        var emptyState = `
                            <div class="flex flex-col items-center justify-center w-full h-[200px] border border-[#EEEEEE] rounded-[14px]">
                                <img src="{{ asset('images/icons/setting-2.svg') }}" alt="empty-state" class="mb-5">
                                <p class="font-bold text-[#7F8190]">No Course Found</p>
                            </div>
                        `;
                        $('.course-list-container').append(emptyState);
                    }


                },
                error: function(xhr){
                    // Handle error
                    console.error(xhr);
                }
            });
        });
    });

    function updatePagination(response) {
        // Ambil informasi pagination dari respons
        var currentPage = response.current_page;
        var lastPage = response.last_page;

        // Bangun ulang link pagination
        var paginationHtml = '';
        for (var i = 1; i <= lastPage; i++) {
            if (i == currentPage) {
                paginationHtml += '<span class="current">' + i + '</span>';
            } else {
                paginationHtml += '<a href="' + response.path + '?page=' + i + '">' + i + '</a>';
            }
        }

        // Update elemen pagination dengan HTML baru
        $('.pagination').html(paginationHtml);
    }
</script>


@endpush
