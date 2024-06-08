@extends('layouts.master')
@section('title', 'Edit Package')

@push('styles')
<style>
    #saveCourseBtn:disabled {
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
    background-color: #2b82fe; /* Mengubah warna latar belakang toggle switch menjadi biru ketika status dipublikasikan */
    }

    #togglePremium:checked + .toggle-switch::before {
    transform: translateX(24px);
    }

    #togglePremium:checked + .toggle-switch {
    background-color: #2b82fe; /* Mengubah warna latar belakang toggle switch menjadi biru ketika status dipublikasikan */
    }
</style>
@endpush

@section('content')

@if ($errors->any())
<div class="flex flex-col gap-5 mt-[30px]">
    <div class="flex items center gap-2 bg-[#FEE2E2] p-4 w-[700px] rounded-[10px]">
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
        <a href="{{ route('dashboard.courses.index') }}" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold">Manage Courses</a>
        <span class="text-[#7F8190] last:text-[#0A090B]">/</span>
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">Edit Paket Soal</a>
    </div>
</div>
<div class="header flex flex-col gap-1 px-5 mt-5">
    <h1 class="font-extrabold text-[30px] leading-[45px]">Edit Paket Soal</h1>
    <p class="text-[#7F8190]">Buat paket soal baru untuk memulai tryout yang baru</p>
</div>

<form enctype="multipart/form-data" method="post" class="flex flex-col gap-[30px] w-[700px] mx-[70px] mt-10" id="createCourseForm"
    action="{{ route('dashboard.packages.update', $package->id) }}">
    @csrf
    @method('PUT')

    <div class="flex gap-5 items-center">
        <input type="file" name="cover_path" id="icon" class="peer hidden" onchange="previewFile()" data-empty="true">
        <div class="relative w-[200px] h-[200px] rounded-full overflow-hidden peer-data-[empty=true]:border-[3px] peer-data-[empty=true]:border-dashed peer-data-[empty=true]:border-[#EEEEEE]">
            <div class="relative file-preview z-10 w-full h-full">
                <img src="{{ Storage::url($package->cover_path) }}" class="thumbnail-icon w-full h-full object-cover" alt="thumbnail">
            </div>
            <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 text-center font-semibold text-sm text-[#7F8190]">Package Icon<br> (Optional)</span>
        </div>
        <button type="button" class="flex shrink-0 p-[8px_20px] h-fit items-center rounded-full bg-[#0A090B] font-semibold text-white" onclick="document.getElementById('icon').click()">
            Add Icon
        </button>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Package Name</p>
        <div class="flex items-center w-[700px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
        <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
            <img src="{{ asset('images/icons/note-favorite-outline.svg') }}" class="w-full h-full object-contain" alt="icon">
        </div>
        <input value="{{ $package->name }}" type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Write your package name" name="name" >
        </div>

        <p class="font-semibold">Description</p>
        <textarea class="h-[150px] p-[14px_16px] rounded-lg border border-[#EEEEEE] focus-within:border-2 focus-within:border-[#0A090B]"
                        placeholder="Write your package total question" name="description">{{ $package->description }}</textarea>
        <div class="grid grid-cols-2 gap-5">
            <div class="flex flex-col">
                <p class="font-semibold">Total Durations</p>
                <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
                    <input value="{{ $package->total_duration }}" type="number" class="font-semibold w-full outline-none" placeholder="0" name="total_duration" step="0" >
                    <span class="mr-[14px] text-[#7F8190]">Minutes</span>
                </div>
            </div>

            <div class="flex flex-col">
                <p class="font-semibold">Total Questions</p>
                <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
                    <input value="{{ $package->total_question }}" type="number" class="font-semibold w-full outline-none"
                        placeholder="0" name="total_question">
                </div>
            </div>
        </div>
    </div>

    {{-- sale start and sale end date --}}
    <div class="grid grid-cols-2 gap-5">
        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Sale Start Date and Time</p>
            <input value="{{ $package->sale_start_at }}"
                type="datetime-local" class="w-[350px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus:border-2 focus:border-[#0A090B]"
                name="sale_start_at" >
        </div>

        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Sale End Date and Time</p>
            <input value="{{ $package->sale_end_at }}"
                type="datetime-local" class="w-[350px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus:border-2 focus:border-[#0A090B]"
                name="sale_end_at" >
        </div>
    </div>

    {{-- start at and end at --}}
    <div class="grid grid-cols-2 gap-5">
        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Kapan Tryout Bisa Mulai Dikerjakan?</p>
            <input value="{{ $package->start_at }}"
                type="datetime-local" class="w-[350px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus:border-2 focus:border-[#0A090B]"
                name="start_at" >
        </div>

        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Kapan Tryout Berakhir?</p>
            <input value="{{ $package->end_at }}"
                type="datetime-local" class="w-[350px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] focus:border-2 focus:border-[#0A090B]"
                name="end_at" >
        </div>
    </div>

    {{-- discount price and price normal --}}
    <div class="grid grid-cols-2 gap-5">
        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Price</p>
            <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
                <span class="mr-[14px] text-[#7F8190]">Rp</span>
                <input type="text" value="{{ $package->price }}" name="price" id="price" class="font-semibold w-full outline-none" placeholder="0.00"  step="1" >
            </div>
        </div>

        <div class="flex flex-col gap-[10px]">
            <p class="font-semibold">Discount Price</p>
            <div class="flex items-center w-full h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
                <span class="mr-[14px] text-[#7F8190]">Rp</span>
                <input type="text" value="{{ $package->discount }}" name="discount" id="discount" class="font-semibold w-full outline-none" placeholder="0.00" step="1">
            </div>
        </div>

    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Status</p>
        <div class="flex items-center gap-5">
            <label for="toggle" class="flex items-center gap-[10px]">
                <input type="checkbox" id="toggle" class="hidden" value="{{ $package->status == 1 ? '1' : '0' }}" name="status" {{ $package->status == 1 ? 'checked' : '' }}>
                <div class="toggle-switch"></div>
                <span id="toggleText" class="text-gray-600">{{ $package->status == 1 ? 'Published' : 'Draft' }}</span>
            </label>
        </div>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Premium</p>
        <div class="flex items-center gap-5">
            <input type="hidden" name="is_premium" value="0">
            <label for="togglePremium" class="flex items-center gap-[10px]">
                <input type="checkbox" id="togglePremium" class="hidden" value="1" name="is_premium" {{ $package->is_premium == 1 ? 'checked' : '' }}>
                <div class="toggle-switch"></div>
                <span id="toggleTextPremium" class="text-gray-600">{{ $package->is_premium == 1 ? 'Is Premium' : 'Is Free' }}</span>
            </label>
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
        <a href="{{ route('dashboard.packages.index') }}" class="w-full h-[52px] p-[14px_20px] bg-[#0A090B] rounded-full font-semibold text-white transition-all duration-300 text-center">Cancel</a>
        <button type="submit" id="saveCourseBtn" class="w-full h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white
            transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center" disabled>Update Course</button>
    </div>

</form>

@endsection

@push('scripts')
<script>
    function previewFile() {
        var preview = document.querySelector('.file-preview');
        var fileInput = document.querySelector('input[type=file]');

        if (fileInput.files.length > 0) {
            var reader = new FileReader();
            var file = fileInput.files[0]; // Get the first file from the input

            reader.onloadend = function () {
                var img = preview.querySelector('.thumbnail-icon'); // Get the thumbnail image element
                img.src = reader.result; // Update src attribute with the uploaded file
                preview.classList.remove('hidden'); // Remove the 'hidden' class to display the preview
            }

            reader.readAsDataURL(file);
            fileInput.setAttribute('data-empty', 'false');
        } else {
            preview.classList.add('hidden'); // Hide preview if no file selected
            fileInput.setAttribute('data-empty', 'true');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        const tncCheckbox = document.getElementById('tncCheckbox');
        const saveCourseBtn = document.getElementById('saveCourseBtn');

        tncCheckbox.addEventListener('change', function() {
            saveCourseBtn.disabled = !this.checked;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('toggle');
        const toggleText = document.getElementById('toggleText');

        toggle.addEventListener('change', function() {
            const status = toggle.checked ? 1 : 0;
            toggleText.textContent = toggle.checked ? 'Publish' : 'Draft';
        });

        const togglePremium = document.getElementById('togglePremium');
        const toggleTextPremium = document.getElementById('toggleTextPremium');

        togglePremium.addEventListener('change', function() {
            const status = togglePremium.checked ? 1 : 0;
            toggleTextPremium.textContent = togglePremium.checked ? 'Is Premium' : 'Is Free';
        });
    });
</script>

<script>
    // Fungsi untuk memformat angka menjadi format mata uang Rupiah saat ditampilkan
    function formatRupiah(angka) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    // Fungsi untuk menambahkan event listener pada input dengan id 'price'
    document.getElementById('discount').addEventListener('input', function(e){
        e.target.value = formatRupiah(e.target.value);
    });

    // Fungsi untuk menambahkan event listener pada input dengan id 'price'
    document.getElementById('price').addEventListener('input', function(e){
        e.target.value = formatRupiah(e.target.value);
    });

    // fungsi untuk menghapus format mata uang Rupiah 10.000 dan mengembalikan ke bentuk angka 10000
    function unformatRupiah(rupiah) {
        return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''), 10);
    }

    // Fungsi untuk menambahkan event listener pada form dengan id 'createCourseForm'
    document.getElementById('createCourseForm').addEventListener('submit', function(e){
        // Menghapus format mata uang Rupiah pada input dengan id 'price' sebelum form disubmit
        const priceInput = document.getElementById('price');
        priceInput.value = unformatRupiah(priceInput.value);

        // Menghapus format mata uang Rupiah pada input dengan id 'discount' sebelum form disubmit
        const discountInput = document.getElementById('discount');
        discountInput.value = unformatRupiah(discountInput.value);
    });
</script>

<script>
    function handleActiveAnchor(element) {
        event.preventDefault();

        const group = element.getAttribute('data-group');
        var categoryId = element.getAttribute('data-category-id');
        document.getElementById('selectedCategoryId').value = categoryId;

        // Reset all elements' aria-checked to "false" within the same data-group
        const allElements = document.querySelectorAll(`[data-group="${group}"][aria-checked="true"]`);
        allElements.forEach(el => {
            el.setAttribute('aria-checked', 'false');
        });

        // Set the clicked element's aria-checked to "true"
        element.setAttribute('aria-checked', 'true');


    }

    function handleActivePublish(element) {
        event.preventDefault();

        const group = element.getAttribute('data-group');

        // Reset all elements' aria-checked to "false" within the same data-group
        const allElements = document.querySelectorAll(`[data-group="${group}"][aria-checked="true"]`);
        allElements.forEach(el => {
            el.setAttribute('aria-checked', 'false');
        });

        // Set the clicked element's aria-checked to "true"
        element.setAttribute('aria-checked', 'true');

        // Set value based on the clicked element
        const isChecked = element.getAttribute('aria-checked') === 'true';
        const input = document.getElementById('published_at'); // Assuming the ID of the input element is 'published_at'

        if (isChecked) {
            // Jika diklik, set nilai input menjadi tanggal saat ini
            const currentDate = new Date().toISOString().slice(0, 10);
            input.value = currentDate;
        } else {
            // Jika tidak diklik, set nilai input menjadi null
            input.value = null;
        }
    }

</script>
@endpush
