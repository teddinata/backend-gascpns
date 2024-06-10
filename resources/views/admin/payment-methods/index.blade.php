@extends('layouts.master')
@section('title', 'Daftar Metode Pembayaran')

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

<div class="flex flex-col px-5 mt-5">
    <div class="w-full flex justify-between items-center">
        <div class="flex flex-col gap-1">
            <p class="font-extrabold text-[30px] leading-[45px]">Daftar Metode Pembayaran</p>
            <p class="text-[#7F8190]">Tampilkan daftar metode pembayaran di sini</p>
        </div>
        <div class="flex items-center space-x-5">
            <form class="search flex items-center w-[500px] h-[52px] p-[10px_16px] rounded-full border border-[#EEEEEE]">
                <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Cari berdasarkan kode transaksi, dll" name="search">
                <button type="submit" class="w-8 h-8 flex items-center justify-center">
                    <img src="{{ asset('images/icons/search.svg') }}" alt="icon">
                </button>
            </form>
            {{-- button edit payment method --}}
            <a href="{{ route('dashboard.payment-methods.create') }}"
            class="h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full
            font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D]">Edit Metode Pembayaran</a>
        </div>
    </div>
</div>

<!-- List Available Banks -->
<div class="transaction-list-container flex flex-col px-5 mt-[30px] gap-[30px]">
    <div class="transaction-list-header flex flex-nowrap justify-between pb-4 pr-10 border-b border-[#EEEEEE]">
        <div class="flex shrink-0 w-[200px]">
            <p class="text-[#7F8190]">Nama Bank</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Kode Bank</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Mata Uang</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Status</p>
        </div>
    </div>
    @forelse ($list_available_banks as $bank)
    <div class="list-items flex flex-nowrap justify-between pr-10">
        <div class="flex shrink-0 w-[200px]">
            <p class="font-semibold">{{ $bank->name }}</p>
        </div>
        <div class="flex shrink-0 w-[150px] items-center justify-center">
            <p>{{ $bank->code }}</p>
        </div>
        <div class="flex  shrink-0 w-[150px] items-center justify-center">
            <p>{{ $bank->currency }}</p>
        </div>
        <div class="flex  shrink-0 w-[150px] items-center justify-center">
            @if ($bank->is_activated == 1)
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]">Active</p>
            @else
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]">Non Active</p>
            @endif
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center w-full h-[300px] border border-[#EEEEEE] rounded-[14px]">
        <img src="{{ asset('images/icons/empty-folder.svg') }}" alt="empty-state" class="mb-5" style="width: 250px">
        <p class="font-bold text-[#7F8190]">Tidak Ada Data</p>
    </div>
    @endforelse

    <!-- Pagination -->
    <div id="pagination" class="flex gap-4 items-center mt-[30px] px-5">
        @if ($list_available_banks->onFirstPage())
            <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]"><<</button>
        @else
            <a href="{{ $list_available_banks->previousPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]"><<</a>
        @endif

        @for ($i = 1; $i <= $list_available_banks->lastPage(); $i++)
            <a href="{{ $list_available_banks->url($i) }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190] {{ $list_available_banks->currentPage() == $i ? 'bg-[#0A090B] text-white' : '' }}">{{ $i }}</a>
        @endfor

        @if ($list_available_banks->hasMorePages())
            <a href="{{ $list_available_banks->nextPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">>></a>
        @else
            <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]">>></button>
        @endif
    </div>
</div>

<!-- List E-Wallets -->
<div class="transaction-list-container flex flex-col px-5 mt-[30px] gap-[30px]">
    <div class="transaction-list-header flex flex-nowrap justify-between pb-4 pr-10 border-b border-[#EEEEEE]">
        <div class="flex shrink-0 w-[200px]">
            <p class="text-[#7F8190]">Nama E-Wallet</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Kode E-Wallet</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Mata Uang</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Status</p>
        </div>
    </div>
    @forelse ($list_ewallets as $ewallet)
    <div class="list-items flex flex-nowrap justify-between pr-10">
        <div class="flex shrink-0 w-[200px]">
            <p class="font-semibold">{{ $ewallet->name }}</p>
        </div>
        <div class="flex  shrink-0 w-[150px] items-center justify-center">
            <p>{{ $ewallet->code }}</p>
        </div>
        <div class="flex  shrink-0 w-[150px] items-center justify-center">
            <p>{{ $ewallet->currency }}</p>
        </div>
        <div class="flex  shrink-0 w-[150px] items-center justify-center">
            @if ($ewallet->is_activated == 1)
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]">Active</p>
            @else
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]">Non Active</p>
            @endif
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center w-full h-[300px] border border-[#EEEEEE] rounded-[14px]">
        <img src="{{ asset('images/icons/empty-folder.svg') }}" alt="empty-state" class="mb-5" style="width: 250px">
        <p class="font-bold text-[#7F8190]">Tidak Ada Data</p>
    </div>
    @endforelse

    <!-- Pagination -->
    <div id="pagination" class="flex gap-4 items-center mt-[30px] px-5">
        @if ($list_ewallets->onFirstPage())
            <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]"><<</button>
        @else
            <a href="{{ $list_ewallets->previousPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]"><<</a>
        @endif

        @for ($i = 1; $i <= $list_ewallets->lastPage(); $i++)
            <a href="{{ $list_ewallets->url($i) }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190] {{ $list_ewallets->currentPage() == $i ? 'bg-[#0A090B] text-white' : '' }}">{{ $i }}</a>
        @endfor

        @if ($list_ewallets->hasMorePages())
            <a href="{{ $list_ewallets->nextPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">>></a>
        @else
            <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]">>></button>
        @endif
    </div>
</div>

@endsection
