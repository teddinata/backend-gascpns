@extends('layouts.master')
@section('title', 'Daftar Transaksi')

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
            <p class="font-extrabold text-[30px] leading-[45px]">Daftar Transaksi</p>
            <p class="text-[#7F8190]">Tampilkan daftar transaksi di sini</p>
        </div>
        <div class="flex items-center space-x-5">
            <form class="search flex items-center w-[500px] h-[52px] p-[10px_16px] rounded-full border border-[#EEEEEE]">
                <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Cari berdasarkan kode transaksi, dll" name="search">
                <button type="submit" class="w-8 h-8 flex items-center justify-center">
                    <img src="{{ asset('images/icons/search.svg') }}" alt="icon">
                </button>
            </form>

        </div>
    </div>
</div>

<div class="transaction-list-container flex flex-col px-5 mt-[30px] gap-[30px]">
    <div class="transaction-list-header flex flex-nowrap justify-between pb-4 pr-10 border-b border-[#EEEEEE]">
        <div class="flex shrink-0 w-[200px]">
            <p class="text-[#7F8190]">Kode Transaksi</p>
        </div>
        <div class="flex shrink-0 w-[200px]">
            <p class="text-[#7F8190]">Nama Pembeli</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Total Pembayaran</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Tanggal Pembayaran</p>
        </div>
        <div class="flex justify-center shrink-0 w-[200px]">
            <p class="text-[#7F8190]">Metode Pembayaran</p>
        </div>
        <div class="flex justify-center shrink-0 w-[200px]">
            <p class="text-[#7F8190]">Status Pembayaran</p>
        </div>
        <div class="flex justify-center shrink-0 w-[150px]">
            <p class="text-[#7F8190]">Aksi</p>
        </div>
    </div>
    @forelse ($transactions as $transaction)
    <div class="list-items flex flex-nowrap justify-between pr-10">
        <div class="flex shrink-0 w-[200px]">
            <p class="font-semibold">{{ $transaction->invoice_code }}</p>
        </div>
        <div class="flex shrink-0 w-[200px]">
            <p>{{ $transaction->student->name }}</p>
        </div>
        <div class="flex shrink-0 w-[150px] items-center justify-center">
            <p>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
        </div>
        <div class="flex shrink-0 w-[150px] items-center justify-center">
            <p>{{ $transaction->payment_date }}</p>
        </div>
        <div class="flex shrink-0 w-[250px] items-center justify-center">
            <p>{{ $transaction->payment_method }}</p>
        </div>
        <div class="flex shrink-0 w-[200px] items-center justify-center">
            @if ($transaction->payment_status == 'PAID')
            <p class="p-[8px_16px] rounded-full bg-[#D5EFFE] font-bold text-sm text-[#066DFE]">{{ $transaction->payment_status }}</p>
            @elseif ($transaction->payment_status == 'PENDING')
            <p class="p-[8px_16px] rounded-full bg-[#FFF2E6] font-bold text-sm text-[#F6770B]">{{ $transaction->payment_status }}</p>
            @elseif ($transaction->payment_status == 'EXPIRED')
            <p class="p-[8px_16px] rounded-full bg-[#dad6d6] font-bold text-sm text-[#6b6b6b]">CANCELLED by System</p>
            @elseif ($transaction->payment_status == 'UNPAID')
            <p class="p-[8px_16px] rounded-full bg-[#FEE2E2] font-bold text-sm text-[#EB5757]">{{ $transaction->payment_status }}</p>
            @elseif ($transaction->payment_status == 'CANCELLED')
            <p class="p-[8px_16px] rounded-full bg-[#FEE2E2] font-bold text-sm text-[#EB5757]">CANCELLED by User</p>
            @endif
        </div>
        <div class="flex shrink-0 w-[150px] items-center justify-center">
            {{-- icon eye use fontawesome --}}
            <a href="{{ route('dashboard.transactions.show', $transaction) }}" class="text-[#007BFF] font-semibold" data-toggle="tooltip" data-placement="top" title="View Transaction">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center w-full h-[300px] border border-[#EEEEEE] rounded-[14px]">
        <img src="{{ asset('images/icons/empty-folder.svg') }}" alt="empty-state" class="mb-5" style="width: 250px">
        <p class="font-bold text-[#7F8190]">Tidak Ada Transaksi</p>
    </div>
    @endforelse
</div>

<div class="flex text-[#7F8190] gap-4 items-center mt-[37px] px-5">
    Menampilkan data {{ $transactions->firstItem() }} hingga {{ $transactions->lastItem() }} dari total {{ $transactions->total() }} data
</div>
<div id="pagination" class="flex gap-4 items-center mt-[30px] px-5">
    @if ($transactions->onFirstPage())
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]"><<</button>
    @else
        <a href="{{ $transactions->previousPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]"><<</a>
    @endif

    @for ($i = 1; $i <= $transactions->lastPage(); $i++)
        <a href="{{ $transactions->url($i) }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190] {{ $transactions->currentPage() == $i ? 'bg-[#0A090B] text-white' : '' }}">{{ $i }}</a>
    @endfor

    @if ($transactions->hasMorePages())
        <a href="{{ $transactions->nextPageUrl() }}" class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">>></a>
    @else
        <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold text-[#7F8190]">>></button>
    @endif
    </div>

@endsection
