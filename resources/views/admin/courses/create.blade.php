@extends('layouts.master')
@section('title', 'Create Course')

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
</style>
@endpush

@section('content')

@if ($errors->any())
<div class="flex flex-col gap-5 px-[70px] mt-[30px]">
    <div class="flex items center gap-2 bg-[#FEE2E2] p-4 w-[500px] rounded-[10px]">
        <img src="{{ asset('images/icons/closed.png') }}" alt="icon" class="w-6 h-6">
        <p class="font-medium text-red-500">{{ $errors->first() }}</p>
    </div>
</div>
@endif

@if (session('success'))
<div class="flex flex-col gap-5 px-[70px] mt-[30px]">
    <div class="flex items center gap-2 bg-[#D5EFFE] p-4 w-[500px] rounded-[10px]">
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
        <a href="#" class="text-[#7F8190] last:text-[#0A090B] last:font-semibold ">New Course</a>
    </div>
</div>
<div class="header flex flex-col gap-1 px-5 mt-5">
    <h1 class="font-extrabold text-[30px] leading-[45px]">New Course</h1>
    <p class="text-[#7F8190]">Provide high quality for best students</p>
</div>

<form enctype="multipart/form-data" method="post" class="flex flex-col gap-[30px] w-[500px] mx-[70px] mt-10" id="createCourseForm"
    action="{{ route('dashboard.courses.store') }}">
    @csrf
    <input type="hidden" name="category_id" id="selectedCategoryId">
    <div class="flex gap-5 items-center">
        <input type="file" name="cover" id="icon" class="peer hidden" onchange="previewFile()" data-empty="true">
        <div class="relative w-[100px] h-[100px] rounded-full overflow-hidden peer-data-[empty=true]:border-[3px] peer-data-[empty=true]:border-dashed peer-data-[empty=true]:border-[#EEEEEE]">
            <div class="relative file-preview z-10 w-full h-full hidden">
                <img src="" class="thumbnail-icon w-full h-full object-cover" alt="thumbnail">
            </div>
            <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 text-center font-semibold text-sm text-[#7F8190]">Icon <br>Course</span>
        </div>
        <button type="button" class="flex shrink-0 p-[8px_20px] h-fit items-center rounded-full bg-[#0A090B] font-semibold text-white" onclick="document.getElementById('icon').click()">
            Add Icon
        </button>
    </div>
    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Course Name</p>
        <div class="flex items-center w-[500px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/note-favorite-outline.svg') }}" class="w-full h-full object-contain" alt="icon">
            </div>
            <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Write your better course name" name="name" required>
        </div>
    </div>
    {{-- <div class="group/category flex flex-col gap-[10px]">
        <p class="font-semibold">Category</p>
        <div class="peer flex items-center p-[12px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <div class="mr-[10px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/bill.svg') }}" class="w-full h-full object-contain" alt="icon">
            </div>
            <select id="category" class="pl-1 font-semibold focus:outline-none w-full text-[#0A090B] invalid:text-[#7F8190] invalid:font-normal appearance-none bg-[url('{{ asset('images/icons/arrow-down.svg') }}')] bg-no-repeat bg-right" name="category_id" required>
                <option value="" disabled selected hidden>Choose one of category</option>
                @forelse ($categories as $category)
                    <option value="{{ $category->id }}" class ="font-semibold">{{ $category->name }}</option>
                @empty
                    <option value="" class="font-semibold">No category available</option>
                @endforelse
            </select>
        </div>
    </div> --}}
    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Category</p>
        <div class="flex gap-5 items-center">
            @forelse ($categories as $category)
            <a href="#" class="group relative flex flex-col w-full items-center gap-5 p-[30px_16px] border border-[#EEEEEE] rounded-[30px] transition-all duration-300 aria-checked:border-2 aria-checked:border-[#0A090B]" data-group="course-type" aria-checked="false" onclick="handleActiveAnchor(this)" data-category-id="{{ $category->id }}">
                <div class="w-[70px] h-[70px] flex shrink-0 overflow-hidden">
                    <!-- Tambahkan kondisi untuk menampilkan ikon sesuai kategori -->
                    @if ($category->name == 'TIU')
                        <img src="{{ asset('images/icons/onboarding.svg') }}" class="w-full h-full" alt="icon">
                    @elseif ($category->name == 'TWK')
                        <img src="{{ asset('images/icons/module.svg') }}" class="w-full h-full" alt="icon">
                    @elseif ($category->name == 'TKP')
                        <img src="{{ asset('images/icons/job.svg') }}" class="w-full h-full" alt="icon">
                    @endif
                </div>
                <span class="text-center mx-auto font-semibold">{{ $category->name }}</span>
                <div class="absolute transform -translate-x-1/2 -translate-y-1/2 top-[24px] right-0 hidden transition-all duration-300 group-aria-checked:block">
                    <img src="{{ asset('images/icons/tick-circle.svg') }}" alt="icon">
                </div>
            </a>
            @empty
                {{-- empty no category available --}}
                <a href="#" class="group relative flex flex-col w-full items-center gap-5 p-[30px_16px] border border-[#EEEEEE] rounded-[30px] transition-all duration-300 aria-checked:border-2 aria-checked:border-[#0A090B]" data-group="course-type" aria-checked="false" onclick="event.preventDefault()">
                    <div class="w-[70px] h-[70px] flex shrink-0 overflow-hidden">
                        <img src="{{ asset('images/icons/onboarding.svg') }}" class="w-full h-full" alt="icon">
                    </div>
                    <span class="text-center mx-auto font-semibold">No Category Available</span>
                    <div class="absolute transform -translate-x-1/2 -translate-y-1/2 top-[24px] right-0 hidden transition-all duration-300 group-aria-checked:block">
                        <img src="{{ asset('images/icons/tick-circle.svg') }}" alt="icon">
                    </div>
                </a>
            @endforelse
        </div>
    </div>
    {{-- <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Publish Date</p>
        <div class="flex gap-[10px] items-center">
            <a href="#" class="group relative flex w-full items-center gap-[14px] p-[14px_16px] border border-[#EEEEEE] rounded-full transition-all duration-300 aria-checked:border-2 aria-checked:border-[#0A090B]" data-group="publish-date" aria-checked="false" onclick="handleActivePublish(this)">
                <input type="hidden" name="published_at" id="published_at" value="">
                <div class="w-[24px] h-[24px] flex shrink-0 overflow-hidden">
                    <img src="{{ asset('images/icons/clock.svg') }}" class="w-full h-full" alt="icon">
                </div>
                <span class="font-semibold">Active Now</span>
                <div class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 right-0 hidden transition-all duration-300 group-aria-checked:block">
                    <img src="{{ asset('images/icons/tick-circle.svg') }}" alt="icon">
                </div>
            </a>
            <a href="#" class="group relative flex w-full items-center gap-[14px] p-[14px_16px] border border-[#EEEEEE] rounded-full transition-all duration-300 aria-checked:border-2 aria-checked:border-[#0A090B] disabled:border-[#EEEEEE]" data-group="publish-date" aria-checked="false" onclick="event.preventDefault()">
                <div class="w-[24px] h-[24px] flex shrink-0 overflow-hidden">
                    <img src="{{ asset('images/icons/calendar-add-disabled.svg') }}" class="w-full h-full" alt="icon">
                </div>
                <span class="font-semibold text-[#EEEEEE]">Schedule for Later</span>
                <div class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 right-0 hidden transition-all duration-300 group-aria-checked:block">
                    <img src="{{ asset('images/icons/tick-circle.svg') }}" alt="icon">
                </div>
            </a>
        </div>
    </div> --}}
    {{-- <div class="group/access flex flex-col gap-[10px]">
        <p class="font-semibold">Access Type</p>
        <div class="peer flex items-center p-[12px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <div class="mr-[10px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/security-user.svg') }}" class="w-full h-full object-contain" alt="icon">
            </div>
            <select id="access" class="pl-1 font-semibold focus:outline-none w-full text-[#0A090B] invalid:text-[#7F8190] invalid:font-normal appearance-none bg-[url('{{ asset('images/icons/arrow-down.svg') }}')] bg-no-repeat bg-right" name="access" >
                <option value="" disabled selected hidden>Choose the access type</option>
                <option value="a" class="font-semibold">Digital Marketing</option>
                <option value="b" class="font-semibold">Web Development</option>
            </select>
        </div>
    </div> --}}

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Passing Grade</p>
        <div class="flex items-center w-[500px] h-[52px] p-[14px_16px] rounded-full border border-[#EEEEEE] transition-all duration-300 focus-within:border-2 focus-within:border-[#0A090B]">
            <div class="mr-[14px] w-6 h-6 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/icons/note-favorite-outline.svg') }}" class="w-full h-full object-contain" alt="icon">
            </div>
            <input type="number" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Passing Grade" name="passing_grade" required>
        </div>
    </div>

    <div class="flex flex-col gap-[10px]">
        <p class="font-semibold">Status</p>
        <div class="flex items-center gap-5">
            <label for="toggle" class="flex items-center gap-[10px]">
                <input type="checkbox" id="toggle" class="hidden" value="0" name="status">
                <div class="toggle-switch"></div>
                <span id="toggleText" class="text-gray-600">Draft</span>
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
        <a href="{{ route('dashboard.courses.index') }}" class="w-full h-[52px] p-[14px_20px] bg-[#0A090B] rounded-full font-semibold text-white transition-all duration-300 text-center">Cancel</a>
        <button type="submit" id="saveCourseBtn" class="w-full h-[52px] p-[14px_20px] bg-[#2B82FE] rounded-full font-bold text-white transition-all duration-300 hover:shadow-[0_4px_15px_0_#2B82FE4D] text-center" disabled>Save Course</button>
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
