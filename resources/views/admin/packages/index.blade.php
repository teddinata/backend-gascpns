@extends('layouts.master')
@section('title', 'Daftar Paket Soal')

@section('content')

@if ($errors->any())
<div class="flex flex-col gap-5 px-5 mt-5">
    <div class="flex items center gap-2 bg-[#FEE2E2] p-4 w-full rounded-[10px]">
        <img src="{{ asset('images/icons/closed.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-red-500">{{ $errors->first() }}</p>
    </div>
</div>
@endif

@if (session('success'))
<div class="flex flex-col gap-5 px-5 mt-5">
    <div class="flex items center gap-2 bg-[#D5EFFE] p-4 w-full rounded-[10px]">
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
            <a href="{{ route('dashboard.packages.create') }}" class="h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full
                font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D]">Add New Course</a>
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
        <div class="flex justify-center shrink-0 w-[250px]">
            <p class="text-[#7F8190]">Price</p>
        </div>
        <div class="flex justify-center shrink-0 w-[250px]">
            <p class="text-[#7F8190]">Discount Price</p>
        </div>
        <div class="flex justify-center shrink-0 w-[250px]">
            <p class="text-[#7F8190]">Sale Start Date</p>
        </div>
        <div class="flex justify-center shrink-0 w-[120px]">
            <p class="text-[#7F8190]">Status</p>
        </div>
        <div class="flex justify-center shrink-0 w-[120px]">
            <p class="text-[#7F8190]">Action</p>
        </div>
    </div>
    @forelse ($packages as $package)
    <div class="list-items flex flex-nowrap justify-between pr-10">
        <div class="flex shrink-0 w-[300px]">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 flex shrink-0 overflow-hidden rounded-full">
                    <img src="{{ Storage::url($package->cover_path) }}" class="object-cover" alt="thumbnail">
                </div>
                <div class="flex flex-col gap-[2px]">
                    <p class="font-bold text-lg">{{ $package->name }}</p>
                    <p class="text-[#7F8190]">Beginners</p>
                </div>
            </div>
        </div>
        <div class="flex shrink-0 w-[150px] items-center justify-center">
            <p class="font-semibold">{{ $package->created_at->format('d M Y H:i') }}</p>
        </div>

        <div class="flex shrink-0 w-[250px] items-center justify-center">
            @php
            $price = $package->price;
            $color = '';
            if ($price > 100000) {
                $color = 'text-blue-500';
            } elseif ($price >= 50000 && $price <= 100000) {
                $color = 'text-orange-500';
            } else {
                $color = 'text-red-500';
            }
            @endphp
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm {{ $color }}">{{ 'Rp ' . number_format($price, 0, ',', '.') }}</p>
        </div>

        <div class="flex shrink-0 w-[250px] items-center justify-center">
            @php
            $discount = $package->discount;
            $color = '';
            if ($discount > 100000) {
                $color = 'text-blue-500';
            } elseif ($discount >= 50000 && $discount <= 100000) {
                $color = 'text-orange-500';
            } else {
                $color = 'text-red-500';
            }
            @endphp
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm {{ $color }}">{{ 'Rp ' . number_format($discount, 0, ',', '.') }}</p>
        </div>

        <div class="flex shrink-0 w-[250px] items-center justify-center">
            <p class="p-[8px_16px] rounded-full bg-green-800 font-bold text-sm text-white">
                {{ $package->sale_start_at ? \Carbon\Carbon::parse($package->sale_start_at)->format('d M Y H:i') : '-' }}
            </p>
        </div>

        <div class="flex shrink-0 w-[120px] items-center justify-center">
            @if ($package->status == 1)
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]">{{ $package->status == 1 ? 'Published' : 'Draft' }}</p>
            @else
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]">{{ $package->status == 1 ? 'Published' : 'Draft' }}</p>
            @endif
        </div>

        <div class="flex shrink-0 w-[120px] items-center">
            <div class="relative h-[41px]">
                <div class="menu-dropdown w-[120px] max-h-[41px] overflow-hidden absolute top-0 p-[10px_16px] bg-white flex flex-col gap-3 border border-[#EEEEEE] transition-all duration-300 hover:shadow-[0_10px_16px_0_#0A090B0D] rounded-[18px]">
                    <button onclick="toggleMaxHeight(this)" class="flex items-center justify-between font-bold text-sm w-full">
                        menu
                        <img src="{{ asset('images/icons/arrow-down.svg') }}" alt="icon">
                    </button>
                    <a href="{{ route('dashboard.packages.show', $package) }}" class="flex items-center justify-between font-bold text-sm w-full">
                        Manage
                    </a>
                    <a href="{{ route('dashboard.tryouts.students.index', $package) }}" class="flex items-center justify-between font-bold text-sm w-full">
                        Students
                    </a>
                    <a href="{{ route('dashboard.packages.edit', $package) }}" class="flex items-center justify-between font-bold text-sm w-full">
                        Edit Paket
                    </a>
                    <form action="{{ route('dashboard.packages.destroy', $package->id) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="flex items-center justify-between font-bold text-sm w-full text-[#FD445E]"
                            onclick="confirmDelete(event, {{ $package->id }})">
                            Hapus
                        </button>
                    </form>
                    {{-- <a href="{{ route('dashboard.courses.destroy', $package) }}"  class="flex items-center justify-between font-bold text-sm w-full text-[#FD445E]"
                        data-confirm-delete="true">Delete</a> --}}
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center w-full h-[300px] border border-[#EEEEEE] rounded-[14px]">
        <img src="{{ asset('images/icons/empty-folder.svg') }}" alt="empty-state" class="mb-5" style="width: 250px">
        <p class="font-bold text-[#7F8190]">No Packages Found</p>
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
    Show data {{ $packages->firstItem() }} to {{ $packages->lastItem() }} of {{ $packages->total() }} total data
</div>
<div id="pagination" class="flex gap-4 items-center mt-[30px] px-5">
    @if ($packages->onFirstPage())
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]"><<</button>
    @else
        <a href="{{ $packages->previousPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]"><<</a>
    @endif

    @for ($i = 1; $i <= $packages->lastPage(); $i++)
        <a href="{{ $packages->url($i) }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190] {{ $packages->currentPage() == $i ? 'bg-[#0A090B] text-white' : '' }}">{{ $i }}</a>
    @endfor

    @if ($packages->hasMorePages())
        <a href="{{ $packages->nextPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">>></a>
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
                document.getElementById('deleteForm').action = "{{ route('dashboard.courses.index') }}" + '/' + id;
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
