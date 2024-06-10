@extends('layouts.master')
@section('title', 'Edit Metode Pembayaran')

@section('content')

<div class="flex flex-col px-5 mt-5">
    <div class="w-full flex justify-between items-center">
        <div class="flex flex-col gap-1">
            <p class="font-extrabold text-2xl leading-9">Edit Metode Pembayaran</p>
            <p class="text-gray-600">Edit metode pembayaran di sini</p>
        </div>
    </div>
</div>

<form action="{{ route('dashboard.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- List Available Banks -->
    <div class="transaction-list-container flex flex-col px-5 mt-10 gap-5">
        <p class="font-bold text-lg">Daftar Bank Tersedia</p>

        @forelse ($list_available_banks as $bank)
        <div class="flex items-center justify-between border-b py-3">
            <div>
                <input type="hidden" name="list_available_banks[{{ $bank->id }}]" value="0"> <!-- Hidden input with default value 0 -->
                <input type="checkbox" name="list_available_banks[{{ $bank->id }}]" value="1" @if ($bank->is_activated)
                checked @endif>
                <span class="ml-3">{{ $bank->name }}</span>
            </div>
            <span class="text-sm text-gray-500">{{ $bank->currency }}</span>
        </div>
        @empty
        <p class="text-gray-500">Tidak ada bank tersedia.</p>
        @endforelse
    </div>

    <!-- List E-Wallets -->
    <div class="transaction-list-container flex flex-col px-5 mt-10 gap-5">
        <p class="font-bold text-lg">Daftar E-Wallet</p>

        @forelse ($list_ewallets as $ewallet)
        <div class="flex items-center justify-between border-b py-3">
            <div>
                <input type="hidden" name="list_ewallets[{{ $ewallet->id }}]" value="0"> <!-- Hidden input with default value 0 -->
                <input type="checkbox" name="list_ewallets[{{ $ewallet->id }}]" value="1" @if ($ewallet->is_activated)
                checked @endif>
                <span class="ml-3">{{ $ewallet->name }}</span>
            </div>
            <span class="text-sm text-gray-500">{{ $ewallet->currency }}</span>
        </div>
        @empty
        <p class="text-gray-500">Tidak ada E-Wallet tersedia.</p>
        @endforelse
    </div>

    <div class="mt-10">
        <button type="submit"
            class="h-14 px-20 bg-blue-500 rounded-full font-bold text-white transition duration-300 hover:shadow-md hover:bg-blue-600">Simpan
            Perubahan</button>
    </div>
</form>

@endsection
