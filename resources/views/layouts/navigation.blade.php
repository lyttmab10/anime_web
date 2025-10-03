<nav x-data="{ open: false, darkMode: false }" x-init="darkMode = localStorage.getItem('darkMode') === 'true' || (localStorage.getItem('darkMode') === null && window.matchMedia('(prefers-color-scheme: dark)').matches)" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-700 dark:text-indigo-300">
                        AnimeHub
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('หน้าแรก') }}
                    </x-nav-link>
                    <x-nav-link :href="route('anime.random')" :active="request()->routeIs('anime.random')">
                        {{ __('สุ่มอนิเมะ') }}
                    </x-nav-link>
                    <x-nav-link :href="route('search.index')" :active="request()->routeIs('search.index')">
                        {{ __('ค้นหา') }}
                    </x-nav-link>
                    <x-nav-link :href="route('anime.compare.form')" :active="request()->routeIs('anime.compare.form')">
                        {{ __('เปรียบเทียบ') }}
                    </x-nav-link>
                    @auth
                    <x-nav-link :href="route('watchlist.index')" :active="request()->routeIs('watchlist.index')">
                        {{ __('ลิสต์ของฉัน') }}
                    </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Dark Mode Toggle -->
                <button 
                    @click="darkMode = !darkMode; 
                            if (darkMode) {
                                document.documentElement.classList.add('dark');
                                localStorage.setItem('darkMode', 'true');
                            } else {
                                document.documentElement.classList.remove('dark');
                                localStorage.setItem('darkMode', 'false');
                            }" 
                    class="mr-4 p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none"
                >
                    <svg x-show="!darkMode" class="w-5 h-5 text-gray-800 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg x-show="darkMode" style="display: none;" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
                
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('โปรไฟล์') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('watchlist.index')">
                            {{ __('ลิสต์อนิเมะ') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('user.friends', Auth::id())">
                            {{ __('เพื่อน') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('dashboard')">
                            {{ __('แดชบอร์ด') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('ออกจากระบบ') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium">เข้าสู่ระบบ</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">สมัครสมาชิก</a>
                </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('หน้าแรก') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('search.index')" :active="request()->routeIs('search.index')">
                {{ __('ค้นหา') }}
            </x-responsive-nav-link>
            @auth
            <x-responsive-nav-link :href="route('watchlist.index')" :active="request()->routeIs('watchlist.index')">
                {{ __('ลิสต์ของฉัน') }}
            </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('โปรไฟล์') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('watchlist.index')">
                    {{ __('ลิสต์อนิเมะ') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.friends', Auth::id())">
                    {{ __('เพื่อน') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard')">
                    {{ __('แดชบอร์ด') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('ออกจากระบบ') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="px-4 space-y-2">
                <a href="{{ route('login') }}" class="block text-sm text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium">เข้าสู่ระบบ</a>
                <a href="{{ route('register') }}" class="block bg-indigo-600 text-white text-center px-4 py-2 rounded hover:bg-indigo-700 transition">สมัครสมาชิก</a>
            </div>
            @endauth
        </div>
    </div>
</nav>