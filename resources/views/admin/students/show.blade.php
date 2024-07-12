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
          <img src="{{ $student->avatar ? asset('storage/' . $student->avatar) : 'https://ui-avatars.com/api/?name=' . $student->name . '&color=7F9CF5&background=EBF4FF'}}"
            alt="Foto Profil" class="w-40 h-40 rounded-full object-cover shadow-lg">
          <h2 class="text-2xl font-semibold mt-5">{{ $student->name }}</h2>

            @if ($student->email_verified_at == null)
                <span class="text-red-500">Belum Terverifikasi</span>
            @else
                <span class="text-green text-green-500">Terverifikasi</span>
            @endif
        </div>
      </div>

      <div class="w-full md:w-2/3">
        <div class="bg-white p-6 rounded-md shadow-md">
          <h3 class="text-xl font-medium mb-3">Informasi Pribadi</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p class="text-gray-600"><span class="font-semibold">Email:</span> {{ $student->email }}</p>
            <p class="text-gray-600"><span class="font-semibold">Telepon:</span> {{ $student->phone }}</p>
            <p class="text-gray-600"><span class="font-semibold">Alamat:</span> {{ $student->address }}</p>
            <p class="text-gray-600"><span class="font-semibold">Tanggal Lahir:</span> {{ $student->birthdate }}</p>
             {{-- <p class="text-gray-600"><span class="font-semibold">Jenis Kelamin:</span> Laki-laki</p> --}}
          </div>
        </div>

        <div class="bg-white p-6 rounded-md shadow-md mt-5">
            <h3 class="text-xl font-medium mb-3">Informasi Akun</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="text-gray-600"><span class="font-semibold">Username:</span> {{ $student->username }}</p>
                <p class="text-gray-600"><span class="font-semibold">Login Terakhir:</span> {{ $student->last_login ? $student->last_login : '-' }}</p>
                <p class="text-gray-600"><span class="font-semibold">Saldo Akun:</span> Rp {{ number_format($student->wallet_balance, 0, ',', '.') }}</p>
                <p class="text-gray-600"><span class="font-semibold">Referral Code:</span> {{ $student->referral_code }}</p>
            </div>
            {{-- <h3 class="text-xl font-medium mt-5 mb-3">Informasi Orang Tua</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="text-gray-600"><span class="font-semibold">Nama Orang Tua:</span> Jane Doe</p>
                <p class="text-gray-600"><span class="font-semibold">Email Orang Tua:</span> jane.doe@example.com</p>
                <p class="text-gray-600"><span class="font-semibold">Telepon Orang Tua:</span> +62 852-9876-5432</p>
            </div> --}}
        </div>

        <div class="bg-white p-6 rounded-md shadow-md mt-5">
            <h3 class="text-xl font-medium mb-3">Informasi Pengajak</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="text-gray-600">
                    <span class="font-semibold">Diajukan oleh: </span>
                    {{ $student->referrer->referredBy->name ?? 'Tidak ada' }}
                </p>
            </div>

            <h3 class="text-xl font-medium mt-5 mb-3">Informasi Rujukan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p class="text-gray-600">
                    <span class="font-semibold">Total Referrals: </span>
                    {{ $student->referrals->count() }}
                </p>
                <div class="text-gray-600">
                    <span class="font-semibold">User Referrals: </span>
                    <ul class="list-disc pl-5 mt-2">
                        @forelse ($student->referrals as $referral)
                            <li>{{ $referral->user->name }}</li>
                        @empty
                            <li>Tidak ada</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>




        {{-- <div class="bg-white p-6 rounded-md shadow-md mt-5">
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
          </div> --}}
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
@endpush

