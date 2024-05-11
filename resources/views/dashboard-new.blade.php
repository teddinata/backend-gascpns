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
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items center">
                    <div class="flex items center justify-center w-12 h-12 bg-[#E0F3FE] dark:bg-[#E0F3FE] rounded-full">
                        <i class="fa-solid fa-book text-2xl" style="color: #0BA7E3;"></i>
                    </div>
                    <div class="ms-4">
                        <p class="text-xl font-normal text-[#676A71] dark:text-gray-400">Total Materi</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-[#0BA7E3]">10 Materi</p>
                </div>
            </div>
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

            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-xl dark:border-gray-700">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-12 h-12 bg-[#FEFDE0] dark:bg-[#FEFDE0] rounded-full">
                        <i class="fa-solid fa-credit-card text-2xl" style="color: #E3C00B;"></i>
                    </div>
                    <div class="ms-4">
                        <p class="text-xl font-normal text-[#676A71] dark:text-gray-400">Total Transaksi User</p>
                    </div>
                </div>
                <div class="mt-6">
                    <p class="text-3xl font-semibold text-[#E3C00B]">{{ $packagesCount }} Transaksi</p>
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
                    <p class="text-3xl font-semibold text-[#E3C00B]">1 Transaksi</p>
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
