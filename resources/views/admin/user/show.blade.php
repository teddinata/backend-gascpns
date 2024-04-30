@extends('layouts.master')
@section('title', 'Detail Siswa')

@push('styles')
@endpush

@section('content')

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{route('dashboard')}}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="{{ route('dashboard.students.index') }}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Student</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Detail Students</a>
    </div>
</div>

<div class="container mx-auto mt-10 p-4">
    <div class="flex flex-col md:flex-row gap-8">
      <div class="w-full md:w-1/3 bg-white p-6 rounded-md shadow-md">
        <div class="flex flex-col items-center">
          <img src="https://placehold.co/150" alt="Foto Profil" class="w-40 h-40 rounded-full object-cover shadow-lg">
          <h2 class="text-2xl font-semibold mt-5">John Doe</h2>
          <p class="text-gray-600">Kelas 10 - IPA</p>
        </div>
      </div>

      <div class="w-full md:w-2/3">
        <div class="bg-white p-6 rounded-md shadow-md">
          <h3 class="text-xl font-medium mb-3">Informasi Pribadi</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p class="text-gray-600"><span class="font-semibold">Email:</span> john.doe@example.com</p>
            <p class="text-gray-600"><span class="font-semibold">Telepon:</span> +62 812-3456-7890</p>
            <p class="text-gray-600"><span class="font-semibold">Alamat:</span> Jl. Anyelir 12, Kota Bandung, Jawa Barat</p>
            <p class="text-gray-600"><span class="font-semibold">Tanggal Lahir:</span> 1 Januari 2000</p>
             <p class="text-gray-600"><span class="font-semibold">Jenis Kelamin:</span> Laki-laki</p>
          </div>
        </div>

        <div class="bg-white p-6 rounded-md shadow-md mt-5">
          <h3 class="text-xl font-medium mb-3">Informasi Akademik</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p class="text-gray-600"><span class="font-semibold">Jurusan:</span> IPA</p>
            <p class="text-gray-600"><span class="font-semibold">Wali Kelas:</span> Bu. Ani</p>
          </div>
          <h3 class="text-xl font-medium mt-5 mb-3">Informasi Orang Tua</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p class="text-gray-600"><span class="font-semibold">Nama Orang Tua:</span> Jane Doe</p>
            <p class="text-gray-600"><span class="font-semibold">Email Orang Tua:</span> jane.doe@example.com</p>
            <p class="text-gray-600"><span class="font-semibold">Telepon Orang Tua:</span> +62 852-9876-5432</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
@endpush

