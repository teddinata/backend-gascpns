@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')
    {{-- <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                {{ __("You're logged in!") }}
            </div>
        </div>
    </div> --}}
{{-- change dashboard bg color --}}

{{-- only can see teacher --}}
@hasrole('teacher')
<div class="bg-[#F3F4F6] dark:bg-[#FBFBFB]">
    <div class="container mx-4 px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Card 1 -->
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#E0F3FE] rounded-full">
                        <i class="fa-solid fa-users text-2xl" style="color: #0BA7E3;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xl font-normal text-[#676A71]">Total Pengguna Terdaftar</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-[#0BA7E3]">{{ $totalUserActive }} Pengguna</p>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#E0F3FE] rounded-full">
                        <i class="fa-solid fa-user-check text-2xl" style="color: #0BA7E3;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xl font-normal text-[#676A71]">Total Pengguna Verifikasi</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-[#0BA7E3]">{{ $totalUserVerified }} Pengguna</p>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#E0F3FE] rounded-full">
                        <i class="fa-solid fa-plane-departure text-2xl" style="color: #0BA7E3;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xl font-normal text-[#676A71]">Total Paket Tryout</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-[#0BA7E3]">{{ $packagesCount }} Paket</p>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#E0F3FE] rounded-full">
                        <i class="fa-solid fa-book text-2xl" style="color: #0BA7E3;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xl font-normal text-[#676A71]">Total Pertanyaan Dibuat</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-[#0BA7E3]">{{ $totalSoal }} Pertanyaan</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mt-6">
            <!-- Card 5 -->
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#93e79e] rounded-full">
                        <i class="fa-solid fa-credit-card text-2xl" style="color: green;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xl font-normal text-green-700">Total Transaksi Sukses</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-green-500">{{ $transactionPaid }} Transaksi</p>
                </div>
            </div>
            <!-- Card 6 -->
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 bg-red-500 rounded-full">
                        <i class="fa-solid fa-hourglass-start text-2xl" style="color: red;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xl font-normal text-[#676A71]">Total Transaksi Tertunda</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-red-500">{{ $transactionUnpaidAndPending }} Transaksi</p>
                </div>
            </div>

             <!-- Full Width Card -->
             <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#E0F3FE] rounded-full">
                        <i class="fa-solid fa-dollar-sign text-2xl" style="color: #0BA7E3;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-xl font-normal text-[#676A71]">Total Pendapatan</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-[#0BA7E3]">{{ 'Rp ' . number_format($totalRevenue, 0, ',', '.') }}
                </div>
            </div>
        </div>

    </div>

</div>

@else
<div class="bg-[#F3F4F6] dark:bg-[#FBFBFB]">
    <div class="container mx-4 px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items center">
                    <div class="flex items center justify-center w-12 h-12 bg-[#E0F3FE] dark:bg-[#E0F3FE] rounded-full">
                        <i class="fa-solid fa-users text-2xl" style="color: #0BA7E3;"></i>
                    </div>
                    <div class="ms-4">
                        <p class="text-xl font-normal text-[#676A71] dark:text-gray-400">Total Pengguna</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-green-500">1 Transaksi</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endhasrole

{{-- <div class="container mx-4 px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
        <div class="flex items center">
            <div class="flex items center justify-center w-12 h-12 bg-[#E0F3FE] dark:bg-[#E0F3FE] rounded-full">
                <i class="fa-solid fa-users text-2xl" style="color: #0BA7E3;"></i>
            </div>
            <div class="ms-4">
                <p class="text-xl font-normal text-[#676A71] dark:text-gray-400">Total Pengguna</p>
            </div>
        </div>
        <div class="mt-6">
            <p class="text-3xl font-semibold text-[#0BA7E3]">1.000.000 Pengguna</p>
        </div>
    </div>

{{-- <div class="container mx-4 px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 bg-[#E0F3FE] dark:bg-[#E0F3FE] rounded-full">
                <i class="fa-solid fa-plane-departure text-2xl" style="color: #0BA7E3;"></i>
            </div>
            <div class="ms-4">
                <p class="text-xl font-normal text-[#676A71] dark:text-gray-400">Total Paket Tryout</p>
            </div>
        </div>
        <div class="mt-6">
            <p class="text-3xl font-semibold text-[#0BA7E3]">{{ $packagesCount }} Paket</p>
        </div>
    </div>
</div> --}}


@endsection
