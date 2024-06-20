<div id="sidebar" class="w-[270px] flex flex-col shrink-0 min-h-screen justify-between p-[30px] border-r border-[#EEEEEE] bg-[#FBFBFB]">
    <div class="w-full flex flex-col gap-[30px]">
        <a href="index.html" class="flex items-center justify-center">
            <img src="{{ asset ('images/logo/logo-gascpns.png') }}" alt="logo">
        </a>
        <ul class="flex flex-col gap-3">
            <li>
                <h3 class="font-bold text-xs text-[#A5ABB2]">DAILY USE</h3>
            </li>

            @role('teacher')
            <li>
                <a href="{{ route('dashboard') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        <i class="fa-solid fa-earth-asia"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Dashboard
                    </p>
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard.packages.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs(['dashboard.packages.*', 'dashboard.tryouts.students.*']) ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/note-favorite.svg') }}" alt="icon"> --}}
                        {{-- <img src="{{ asset ('images/icons/3dcube.svg') }}" alt="icon"> --}}
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                    {{ request()->routeIs(['dashboard.packages.*', 'dashboard.tryouts.students.*']) ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Paket Soal
                    </p>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.courses.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard.courses.*') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/note-favorite.svg') }}" alt="icon"> --}}
                        {{-- <img src="{{ asset ('images/icons/3dcube.svg') }}" alt="icon"> --}}
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard.courses.*') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                         Soal
                    </p>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.students.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard.students.*') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/profile-2user-outline.svg') }}" alt="icon"> --}}
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard.students.*') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Siswa
                    </p>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.mentor.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard.mentor.*') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/profile-2user-outline.svg') }}" alt="icon"> --}}
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard.mentor.*') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Mentor
                    </p>
                </a>
            </li>

            {{-- transaction --}}
            <li>
                <a href="{{ route('dashboard.transactions.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard.transactions.*') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/profile-2user-outline.svg') }}" alt="icon"> --}}
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard.transactions.*') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Transaksi
                    </p>
                </a>
            </li>

            {{-- payment method --}}
            <li>
                <a href="{{ route('dashboard.payment-methods.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard.payment-methods.*') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/profile-2user-outline.svg') }}" alt="icon"> --}}
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard.payment-methods.*') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Metode Pembayaran
                    </p>
                </a>
            </li>

            {{-- notifications --}}
            <li>
                <a href="{{ route('dashboard.notifications.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard.notifications.*') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/profile-2user-outline.svg') }}" alt="icon"> --}}
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard.notifications.*') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Notifikasi
                    </p>
                </a>
            </li>
            @endrole

            @role('student')
            <li>
                <a href="{{ route('dashboard') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        <i class="fa-solid fa-earth-asia"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Dashboard
                    </p>
                </a>
            </li>

            <li>
                <a href="{{ route('dashboard.learning.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard.learning.*') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/3dcube.svg') }}" alt="icon"> --}}
                        <i class="fa-solid fa-file-pen"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard.learning.*') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        My Tryout
                    </p>
                </a>
            </li>
            @endrole

            <li>
                <a href="" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        <img src="{{ asset ('images/icons/sms-tracking.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold transition-all duration-300 hover:text-white">Messages</p>
                    <div class="notif w-5 h-5 flex shrink-0 rounded-full items-center justify-center bg-[#F6770B]">
                        <p class="font-bold text-[10px] leading-[15px] text-white">12</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        <img src="{{ asset ('images/icons/chart-2.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold transition-all duration-300 hover:text-white">Analytics</p>
                </a>
            </li>
        </ul>
        <ul class="flex flex-col gap-3">
            <li>
                <h3 class="font-bold text-xs text-[#A5ABB2]">OTHERS</h3>
            </li>

            @role('teacher')
            {{-- blog --}}
            <li>
                <a href="{{ route('dashboard.blogs.index') }}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11
                    {{ request()->routeIs('dashboard.blogs.*') ? 'bg-[#2B82FE] hover:bg-[#2B82FE] text-white' : 'bg-[#FFFFFF] hover:bg-[#2B82FE] text-[#0c0f13]' }}
                    transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        {{-- <img src="{{ asset('images/icons/note-favorite-outline.svg') }}" alt="icon"> --}}
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <p class="font-semibold transition-all duration-300
                        {{ request()->routeIs('dashboard.blogs.*') ? 'hover:text-white' : 'text-[#000000] hover:text-white' }}">
                        Blog
                    </p>
                </a>
            </li>


            <li>
                <a href="" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        <img src="{{ asset ('images/icons/3dcube.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold transition-all duration-300 hover:text-white">Rewards</p>
                </a>
            </li>
            <li>
                <a href="" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        <img src="{{ asset ('images/icons/code.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold transition-all duration-300 hover:text-white">A.I Plugins</p>
                </a>
            </li>
            <li>
                <a href="" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                    <div>
                        <img src="{{ asset ('images/icons/setting-2.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold transition-all duration-300 hover:text-white">Settings</p>
                </a>
            </li>
            <li>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                        <div>
                            <img src="{{ asset ('images/icons/security-safe.svg') }}" alt="icon">
                        </div>
                        <p class="font-semibold transition-all duration-300 hover:text-white">Logout</p>
                    </button>
                </form>
            </li>
            @endrole
        </ul>
    </div>
    <a href="">
        <div class="w-full flex gap-6 items-center p-4 rounded-[14px] bg-[#0A090B] mt-[30px]">
            <div>
                <img src="{{ asset ('images/logo/favicon.png') }}" alt="icon" class="w-14 h-14">
            </div>
            <div class="flex flex-col gap-[2px]">
                <p class="font-semibold text-white text-base">Copyright &copy; 2024</p>
                <p class="text-sm leading-[21px] text-[#A0A0A0]">All rights reserved</p>
            </div>
        </div>
    </a>
</div>
