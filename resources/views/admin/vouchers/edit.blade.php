@extends('layouts.master')
@section('title', 'Edit Voucher')

@push('styles')
<style>
    #saveVoucherBtn:disabled {
        background-color: #CCCCCC;
        color: #666666;
        cursor: not-allowed;
    }

    .toggle-switch {
        width: 48px;
        height: 24px;
        background-color: #ccc;
        border-radius: 12px;
        position: relative;
        cursor: pointer;
    }

    .toggle-switch::before {
        content: "";
        width: 20px;
        height: 20px;
        background-color: #fff;
        border-radius: 50%;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;
    }

    #toggle:checked + .toggle-switch::before {
        transform: translateX(24px);
    }

    #toggle:checked + .toggle-switch {
        background-color: #2b82fe;
    }
</style>
@endpush

@section('content')

@if ($errors->any())
<div class="flex flex-col gap-5 mt-[30px]">
    <div class="flex items-center gap-2 bg-[#FEE2E2] p-4 w-[700px] rounded-[10px]">
        <img src="{{ asset('images/icons/closed.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-red-500">{{ $errors->first() }}</p>
    </div>
</div>
@endif

@if (session('success'))
<div class="flex flex-col gap-5 mt-[30px]">
    <div class="flex items center gap-2 bg-[#D5EFFE] p-4 w-[700px] rounded-[10px]">
        <img src="{{ asset('images/icons/shield-check.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-green-500">{{ session('success') }}</p>
    </div>
</div>
@endif

<div class="flex flex-col gap-10 px-5 mt-5">
    <div class="breadcrumb flex items-center gap-[30px]">
        <a href="{{route('dashboard')}}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Home</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="{{ route('dashboard.vouchers.index') }}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Vouchers</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Edit Voucher</a>
    </div>
</div>
<div class="header flex flex-col gap-1 px-5 mt-5">
    <h1 class="font-extrabold text-[30px] leading-[45px]">Edit Voucher</h1>
    <p class="text-[#7F8190]">Edit voucher yang ada untuk memperbarui diskon</p>
</div>

<form method="post" class="flex flex-col gap-[30px] w-[700px] mx-[70px] mt-10" id="editVoucherForm" action="{{ route('dashboard.vouchers.update', $voucher->id) }}">
    @csrf
    @method('PUT')

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Kode Voucher</p>
        <div class="flex items-center w-[700px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Masukkan kode voucher" name="code" value="{{ old('code', $voucher->code) }}">
        </div>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Tipe Diskon</p>
        <div class="flex items-center w-[700px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <select name="discount_type" id="discountTypeSelect" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none">
                <option value="percentage" {{ old('discount_type', $voucher->discount_type) == 'percentage' ? 'selected' : '' }}>Persentase</option>
                <option value="fixed" {{ old('discount_type', $voucher->discount_type) == 'fixed' ? 'selected' : '' }}>Tetap</option>
            </select>
        </div>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Jumlah Diskon (% atau Rp)</p>
        <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <input type="number" name="discount_amount" class="font-semibold w-full outline-none" placeholder="0" step="0.01" value="{{ old('discount_amount', $voucher->discount_amount) }}">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Tanggal Mulai</p>
            <input type="date" name="valid_from" class="w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus:border-2 focus:border-[#0A090B]" value="{{ old('valid_from', $voucher->valid_from) }}">
        </div>

        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Tanggal Kadaluarsa</p>
            <input type="date" name="valid_to" class="w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus:border-2 focus:border-[#0A090B]" value="{{ old('valid_to', $voucher->valid_to) }}">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Minimum Quantity</p>
            <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
                <input type="number" name="min_quantity" class="font-semibold w-full outline-none" placeholder="0" step="1" value="{{ old('min_quantity', $voucher->min_quantity) }}">
            </div>
        </div>

        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Jumlah Pembelian Minimum (Rp)</p>
            <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
                <input type="number" name="min_purchase" class="font-semibold w-full outline-none" placeholder="0.00" step="0.01" value="{{ old('min_purchase', $voucher->min_purchase) }}">
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Diskon Maksimum</p>
        <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <input type="number" name="max_discount" class="font-semibold w-full outline-none" placeholder="0.00" step="0.01" value="{{ old('max_discount', $voucher->max_discount) }}">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Batas Penggunaan</p>
            <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
                <input type="number" name="usage_limit" class="font-semibold w-full outline-none" placeholder="0" value="{{ old('usage_limit', $voucher->usage_limit) }}">
            </div>
        </div>

        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Penggunaan per Pengguna</p>
            <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
                <input type="number" name="usage_per_user" class="font-semibold w-full outline-none" placeholder="0" value="{{ old('usage_per_user', $voucher->usage_per_user) }}">
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Status</p>
        <div class="flex items-center gap-5">
            <label for="toggle" class="flex items-center gap-[10px]">
                <input type="checkbox" id="toggle" class="hidden" name="is_active" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                <div class="toggle-switch"></div>
                <span id="toggleText" class="text-gray-600">{{ old('is_active', $voucher->is_active) ? 'Active' : 'Inactive' }}</span>
            </label>
            <!-- Hidden input to hold the actual value of is_active -->
            <input type="hidden" id="is_active" name="is_active" value="{{ old('is_active', $voucher->is_active) ? '1' : '0' }}">
        </div>
    </div>



    <label class="font-semibold flex items-center gap-[10px]">
        <input
            type="checkbox"
            name="tnc"
            id="tncCheckbox"
            class="w-[24px] h-[24px] appearance-none checked:border-[3px] checked:border-solid checked:border-white rounded-full checked:bg-[#2B82FE] ring ring-[#EEEEEE]"
        />
        I have read terms and conditions
    </label>

    <div class="flex items-center gap-5">
        <a href="{{ route('dashboard.vouchers.index') }}" class="w-full h-[52px] p-[14px_20px] bg-[#0A090B] rounded-full font-semibold text-white transition-all duration-300 text-center">Cancel</a>
        <button type="submit" id="saveVoucherBtn" class="w-full h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center" disabled>Update Voucher</button>
    </div>

</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('toggle');
        const toggleText = document.getElementById('toggleText');
        const isActiveInput = document.getElementById('is_active');

        toggle.addEventListener('change', function() {
            const value = toggle.checked ? 1 : 0;
            isActiveInput.value = value; // Update hidden input value
            toggleText.textContent = toggle.checked ? 'Active' : 'Inactive';
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        const tncCheckbox = document.getElementById('tncCheckbox');
        const saveVoucherBtn = document.getElementById('saveVoucherBtn');

        tncCheckbox.addEventListener('change', function() {
            saveVoucherBtn.disabled = !this.checked;
        });
    });
</script>
@endpush
