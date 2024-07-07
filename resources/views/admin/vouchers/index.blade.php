@extends('layouts.master')
@section('title', 'Manage Vouchers')

@section('content')
<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{ route('dashboard') }}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Vouchers</a>
    </div>
</div>

<div class="header flex flex-col gap-1 px-5 mt-5">
    <h1 class="font-extrabold text-[30px] leading-[45px]">Manage Vouchers</h1>
    <p class="text-[#7F8190]">Daftar voucher yang tersedia</p>
</div>

<div class="flex justify-end mt-5">
    <a href="{{ route('dashboard.vouchers.create') }}" class="bg-[#0A090B] text-white p-[14px_20px] rounded-full font-semibold">Add New Voucher</a>
</div>

<table class="w-full mt-5 bg-white shadow-md rounded-lg">
    <thead>
        <tr class="bg-gray-200 text-left text-gray-600 uppercase text-sm">
            <th class="py-3 px-4">#</th>
            <th class="py-3 px-4">Kode Voucher</th>
            <th class="py-3 px-4">Tipe Diskon</th>
            <th class="py-3 px-4">Jumlah Diskon</th>
            <th class="py-3 px-4">Pembelian Minimum</th>
            <th class="py-3 px-4">Diskon Maksimum</th>
            <th class="py-3 px-4">Tanggal Kadaluarsa</th>
            <th class="py-3 px-4">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($vouchers as $voucher)
            <tr class="border-b">
                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                <td class="py-3 px-4">{{ $voucher->code }}</td>
                <td class="py-3 px-4">{{ ucfirst($voucher->discount_type) }}</td>
                @if ($voucher->discount_type == 'percentage')
                    <td class="py-3 px-4">{{ $voucher->discount_amount }}%</td>
                @else
                    <td class="py-3 px-4">Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}</td>
                @endif
                <td class="py-3 px-4">{{ $voucher->min_purchase }}</td>
                <td class="py-3 px-4">Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</td>
                <td class="py-3 px-4">{{ $voucher->valid_to }}</td>
                <td class="py-3 px-4 flex gap-2">
                    <a href="{{ route('dashboard.vouchers.edit', $voucher->id) }}" class="text-blue-500">Edit</a>
                    <form action="{{ route('dashboard.vouchers.destroy', $voucher->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <div class="flex flex-col items-center justify-center w-full h-[300px] border border-[#EEEEEE] rounded-[14px]">
                <img src="{{ asset('images/icons/empty-folder.svg') }}" alt="empty-state" class="mb-5" style="width: 250px">
                <p class="font-bold text-[#7F8190]">No Vouchers Found</p>
            </div>
        @endforelse
    </tbody>
</table>
@endsection
