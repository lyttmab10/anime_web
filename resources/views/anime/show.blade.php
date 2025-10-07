<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $anime->title }} - AnimeHub</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #f5f5f5;
        }
        .anime-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .anime-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }
        .genre-container::before,
        .genre-container::after {
            display: none !important;
            content: none !important;
        }
        .genre-container span::before,
        .genre-container span::after {
            display: none !important;
            content: none !important;
        }
        .genre-container * {
            position: relative;
        }
        .genre-container *::before,
        .genre-container *::after {
            display: none !important;
            content: none !important;
        }
        /* Ensure no separator appears between genre span elements */
        .genre-container [class*="flex"] span + span::before,
        .genre-container [class*="flex"] span + span::after {
            display: none !important;
            content: none !important;
        }
        
        /* Dropdown menu styles */
        .dropdown-menu {
            transition: opacity 0.2s, transform 0.2s;
        }
        .dropdown-menu:not(.hidden) {
            opacity: 1;
            transform: translateY(0);
        }
        .dropdown-menu.hidden {
            opacity: 0;
            transform: translateY(-0.5rem);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="container mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <a href="{{ route('home') }}" class="text-3xl font-bold text-indigo-700">AnimeHub</a>
                    <nav class="flex items-center space-x-4">
                        <ul class="flex space-x-4 mr-6">
                            <li><a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600 font-medium">หน้าแรก</a></li>
                            <li><a href="{{ route('anime.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium">อนิเมะทั้งหมด</a></li>
                            <li><a href="{{ route('search.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium">ค้นหา</a></li>
                        </ul>
                        @auth
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 hover:text-indigo-600">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">โปรไฟล์</a>
                                    <a href="{{ route('watchlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ลิสต์อนิเมะ</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ออกจากระบบ</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600 font-medium">เข้าสู่ระบบ</a>
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">สมัครสมาชิก</a>
                            </div>
                        @endauth
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <!-- Anime Detail Section -->
            <section class="mb-12">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/4">
                    @if($anime->image_url)
                        <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-auto object-contain max-h-64" />
                    @else
                        <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed w-full h-64 flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400">No Image</span>
                        </div>
                    @endif
                </div>
                <div class="p-4 md:w-3/4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="font-bold text-3xl mb-2 text-gray-800 dark:text-white">{{ $anime->title }}</h1>
                            <div class="flex items-center mb-4">
                                <span class="text-yellow-500 mr-2 text-xl">★</span>
                                <span class="font-bold text-xl text-gray-800 dark:text-white">{{ $anime->rating }}/10</span>
                                @if($anime->is_trending)
                                    <span class="ml-4 bg-red-500 text-white text-sm px-3 py-1 rounded-full">มาแรง</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mt-1">
                            <button 
    onclick="toggleWatchlist({{ $anime->id }})" 
    id="watchlist-btn-{{ $anime->id }}" 
    class="bg-indigo-600 text-white px-3 py-1.5 text-sm rounded hover:bg-indigo-700 transition"
>
    เพิ่มในลิสต์
</button>
                            <button onclick="document.getElementById('reviews').scrollIntoView({ behavior: 'smooth' });" class="bg-gray-600 dark:bg-gray-700 text-white px-3 py-1.5 text-sm rounded hover:bg-gray-700 dark:hover:bg-gray-600 transition">
                                ให้คะแนน
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-1 mb-2">
                        <div class="text-sm py-1 px-2 bg-gray-100 dark:bg-gray-700 rounded">
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">ปี:</span><br>
                                {{ $anime->release_date ? $anime->release_date->format('Y') : '-' }}</p>
                        </div>
                        <div class="text-sm py-1 px-2 bg-gray-100 dark:bg-gray-700 rounded">
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">สตูดิโอ:</span><br> {{ $anime->studio ?: '-' }}</p>
                        </div>
                        <div class="text-sm py-1 px-2 bg-gray-100 dark:bg-gray-700 rounded">
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">ตอน:</span><br> {{ $anime->episodes ?: '-' }}</p>
                        </div>
                        <div class="text-sm py-1 px-2 bg-gray-100 dark:bg-gray-700 rounded">
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">ซีซั่น:</span><br> {{ $anime->season ?: '-' }}</p>
                        </div>
                        <div class="text-sm py-1 px-2 bg-gray-100 dark:bg-gray-700 rounded">
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">สถานะ:</span><br> 
                                @if($anime->status == 'currently_airing')
                                    ออกอากาศ
                                @elseif($anime->status == 'finished_airing')
                                    จบแล้ว
                                @else
                                    ยังไม่เริ่ม
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <h2 class="font-bold text-lg mb-1 text-gray-800 dark:text-white">เรื่องย่อ</h2>
                        <p class="text-gray-700 dark:text-gray-300 text-sm leading-tight">
                            {{ $anime->description ?: 'ไม่มีข้อมูลเรื่องย่อ' }}
                        </p>
                    </div>
                    
                    @if($anime->genres && is_array($anime->genres))
                        <div class="mb-2 genre-container">
                            <span class="font-bold text-gray-700 dark:text-gray-300 text-sm">แนว:</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($anime->genres as $genre)
                                    <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 text-xs px-2 py-0.5 rounded-full">{{ $genre }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($anime->official_site)
                        <div class="mb-4">
                            <a href="{{ $anime->official_site }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                ดูข้อมูลเพิ่มเติมที่เว็บไซต์ทางการ
                            </a>
                        </div>
                    @endif
                    
                    @if($anime->trailer_url)
                        <div class="mb-4">
                            <button class="bg-red-600 dark:bg-red-700 text-white px-4 py-2 rounded hover:bg-red-700 dark:hover:bg-red-600 transition flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                                ดูตัวอย่าง (Trailer)
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
                @if($anime->characters && is_array($anime->characters))
                    <div class="p-4 border-t dark:border-gray-700">
                        <h2 class="font-bold text-lg mb-2 text-gray-800 dark:text-white">ตัวละครหลัก</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach(array_slice($anime->characters, 0, 8) as $character)
                                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded text-center">
                                    <div class="bg-gray-200 dark:bg-gray-600 border-2 border-dashed rounded-full w-12 h-12 mx-auto mb-1" />
                                    <p class="font-medium text-xs text-gray-800 dark:text-white">{{ $character }}</p>
                                    <p class="text-[0.6rem] text-gray-600 dark:text-gray-400">นักพากย์</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
        </div>
    </section>

    <!-- Reviews Section -->
    <section id="reviews" class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">รีวิวและเรตติ้ง</h2>
        
        <!-- Rating Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center mb-4">
                <div class="text-4xl font-bold text-indigo-700 dark:text-indigo-400 mr-6">
                    {{ number_format($anime->average_rating, 1) }}
                </div>
                <div>
                    <div class="flex items-center mb-2">
                        <span class="text-yellow-500 mr-2">★</span>
                        <span class="font-bold text-gray-800 dark:text-white">{{ $anime->reviews->count() }} รีวิว</span>
                    </div>
                </div>
            </div>
            
            <!-- Rating Distribution -->
            <div class="mb-4">
                <div class="flex items-center mb-1">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">5 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-500 rounded-full" style="width: {{ $anime->reviews->where('rating', 5)->count() > 0 ? ($anime->reviews->where('rating', 5)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 5)->count() }}</span>
                </div>
                <div class="flex items-center mb-1">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">4 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-400 rounded-full" style="width: {{ $anime->reviews->where('rating', 4)->count() > 0 ? ($anime->reviews->where('rating', 4)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 4)->count() }}</span>
                </div>
                <div class="flex items-center mb-1">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">3 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-300 rounded-full" style="width: {{ $anime->reviews->where('rating', 3)->count() > 0 ? ($anime->reviews->where('rating', 3)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 3)->count() }}</span>
                </div>
                <div class="flex items-center mb-1">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">2 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-200 rounded-full" style="width: {{ $anime->reviews->where('rating', 2)->count() > 0 ? ($anime->reviews->where('rating', 2)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 2)->count() }}</span>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">1 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-100 rounded-full" style="width: {{ $anime->reviews->where('rating', 1)->count() > 0 ? ($anime->reviews->where('rating', 1)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 1)->count() }}</span>
                </div>
            </div>
        </div>
        
        <!-- Add Review Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            @auth
            <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">เขียนรีวิวของคุณ</h3>
            <form method="POST" action="{{ route('reviews.store', $anime) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">ให้คะแนน</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="rating-btn text-2xl focus:outline-none" data-rating="{{ $i }}">
                                <span class="star-icon text-gray-400 hover:text-yellow-500 cursor-pointer">☆</span>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="0">
                </div>
                <div class="mb-4">
                    <label for="review-text" class="block text-gray-700 dark:text-gray-300 mb-2">รีวิว</label>
                    <textarea id="review-text" name="review" rows="4" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition" placeholder="เขียนรีวิวของคุณที่นี่...">{{ old('review') }}</textarea>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                    </svg>
                    ส่งรีวิว
                </button>
            </form>
            @else
            <div class="text-center py-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-indigo-100 dark:bg-indigo-900/30 p-4 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V15H11M11,13H13V11H11" />
                        </svg>
                    </div>
                </div>
                <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-white">เขียนรีวิวของคุณ</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">ร่วมแบ่งปันความคิดเห็นของคุณเกี่ยวกับอนิเมะเรื่องนี้กับชุมชนของเรา</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        เข้าสู่ระบบ
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        สมัครสมาชิก
                    </a>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-4">สร้างบัญชีเพื่อเข้าร่วมชุมชนของเราและแบ่งปันความคิดเห็นของคุณ!</p>
            </div>
            @endauth
        </div>
        
        <!-- Reviews List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">รีวิวจากผู้ใช้งาน</h3>
            
            @if($anime->reviews->count() > 0)
                @foreach($anime->reviews as $review)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6 last:border-0 last:mb-0 last:pb-0">
                    <div class="flex items-start">
                        <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed rounded-full w-12 h-12 flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-gray-700 dark:text-gray-300 font-bold text-lg">{{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center justify-between mb-2">
                                <div>
                                    <div class="font-bold text-gray-800 dark:text-white text-lg">{{ $review->user->name ?? 'ผู้ใช้ไม่ระบุ' }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $review->created_at->format('d F Y') }}</div>
                                </div>
                                <div class="flex items-center mt-2 sm:mt-0">
                                    <div class="flex text-yellow-500 mr-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <span class="text-xl">★</span>
                                            @else
                                                <span class="text-gray-300 dark:text-gray-600 text-xl">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-gray-800 dark:text-white font-medium">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                            @if($review->review)
                            <p class="text-gray-700 dark:text-gray-300 mb-4 leading-relaxed">{{ $review->review }}</p>
                            @endif

                        </div>
                        @if(Auth::check() && Auth::id() == $review->user_id)
                            <div class="relative inline-block text-left mt-2" x-data="{ open: false }">
                                <button type="button" class="text-gray-500 hover:text-gray-700 text-sm" @click="open = !open; $event.stopPropagation()">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5" style="display: none;">
                                    <div class="py-1">
                                        <button type="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                            @click="openEditReviewModal({{ $review->id }}, {{ $review->rating }}, '{{ addslashes($review->review ?? '') }}'); open = false">
                                            แก้ไข
                                        </button>
                                        <form method="POST" action="{{ route('reviews.destroy', $anime) }}" class="block" onsubmit="return confirm('คุณต้องการลบความคิดเห็นนี้ใช่หรือไม่?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                ลบ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-gray-600 dark:text-gray-400">ยังไม่มีรีวิวสำหรับอนิเมะเรื่องนี้</p>
            @endif
        </div>
    </section>

    <!-- Similar Anime Section -->
    @if($similarAnime->count() > 0)
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">คุณอาจชอบเรื่องนี้</h2>
        
        <!-- Grid of similar anime - 4 columns -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($similarAnime as $similar)
                <div class="anime-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        @if($similar->image_url)
                            <img src="{{ $similar->image_url }}" alt="{{ $similar->title }}" class="w-full h-48 object-cover rounded-t-lg" />
                        @else
                            <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed rounded-t-lg w-full h-48 flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400">No Image</span>
                            </div>
                        @endif
                        @if($similar->is_trending)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">มาแรง</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-1 truncate text-gray-800 dark:text-white" title="{{ $similar->title }}">
                            <a href="{{ route('anime.show', $similar->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $similar->title }}</a>
                        </h3>
                        <div class="flex items-center mb-2">
                            <span class="text-yellow-500 mr-1">★</span>
                            <span class="text-gray-800 dark:text-white">{{ $similar->rating }}/10</span>
                            @if($similar->release_date)
                                <span class="mx-2 text-gray-600 dark:text-gray-400">•</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $similar->release_date->format('Y') }}</span>
                            @endif
                        </div>
                        @if($similar->genres && is_array($similar->genres))
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach(array_slice($similar->genres, 0, 2) as $genre)
                                    <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 text-xs px-2 py-1 rounded">{{ $genre }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingButtons = document.querySelectorAll('.rating-btn');
        const ratingInput = document.getElementById('rating-input');
        
        // Update rating input and star appearance when a star is clicked
        ratingButtons.forEach(button => {
            button.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = rating;
                
                // Update the appearance of all stars
                const allStars = document.querySelectorAll('.star-icon');
                allStars.forEach((star, index) => {
                    if (index < rating) {
                        star.textContent = '★';  // Filled star
                        star.className = 'star-icon text-yellow-500 cursor-pointer';
                    } else {
                        star.textContent = '☆';  // Empty star
                        star.className = 'star-icon text-gray-400 hover:text-yellow-500 cursor-pointer';
                    }
                });
            });
        });
        
        // Add hover effect for stars
        ratingButtons.forEach(button => {
            button.addEventListener('mouseover', function() {
                const currentRating = parseInt(this.getAttribute('data-rating'));
                const allStars = document.querySelectorAll('.star-icon');
                const selectedRating = parseInt(ratingInput.value);
                
                // Highlight stars up to current hover position
                allStars.forEach((star, index) => {
                    if (index < currentRating) {
                        if (index >= selectedRating) {
                            star.textContent = '★';
                            star.className = 'star-icon text-yellow-400 cursor-pointer';
                        }
                    } else if (index >= selectedRating) {
                        star.textContent = '☆';
                        star.className = 'star-icon text-gray-400 cursor-pointer';
                    }
                });
            });
            
            // Restore selection when mouse leaves
            button.addEventListener('mouseout', function() {
                const selectedRating = parseInt(ratingInput.value);
                const allStars = document.querySelectorAll('.star-icon');
                
                allStars.forEach((star, index) => {
                    if (index < selectedRating) {
                        star.textContent = '★';
                        star.className = 'star-icon text-yellow-500 cursor-pointer';
                    } else {
                        star.textContent = '☆';
                        star.className = 'star-icon text-gray-400 hover:text-yellow-500 cursor-pointer';
                    }
                });
            });
        });
        
        // Handle like/dislike functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.like-btn')) {
                e.preventDefault();
                const button = e.target.closest('.like-btn');
                const animeId = button.dataset.animeId;
                const reviewId = button.dataset.reviewId;
                
                fetch(`/anime/${animeId}/reviews/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        review_id: reviewId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.likes !== undefined) {
                        button.querySelector('.like-count').textContent = data.likes + ' ถูกใจ';
                    } else {
                        alert(data.error || 'เกิดข้อผิดพลาด');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            
            if (e.target.closest('.dislike-btn')) {
                e.preventDefault();
                const button = e.target.closest('.dislike-btn');
                const animeId = button.dataset.animeId;
                const reviewId = button.dataset.reviewId;
                
                fetch(`/anime/${animeId}/reviews/dislike`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        review_id: reviewId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.dislikes !== undefined) {
                        button.querySelector('.dislike-count').textContent = data.dislikes + ' ไม่ถูกใจ';
                    } else {
                        alert(data.error || 'เกิดข้อผิดพลาด');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

function showToast(message, isSuccess = true) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white ${isSuccess ? 'bg-green-500' : 'bg-red-500'} transition-opacity duration-300`;
    toast.textContent = message;
    document.body.appendChild(toast);

    // ลบทิ้งหลัง 3 วินาที
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

async function toggleWatchlist(animeId) {
    const btn = document.getElementById(`watchlist-btn-${animeId}`);
    const currentText = btn.textContent;

    try {
        const response = await fetch('/watchlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                anime_id: animeId,
                status: 'watching'
            })
        });

        if (response.ok) {
            const result = await response.json();
            if (result.added) {
                btn.textContent = 'ลบจากลิสต์';
                btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                btn.classList.add('bg-red-600', 'hover:bg-red-700');
                showToast('เพิ่มในลิสต์แล้ว', true);
            } else {
                btn.textContent = 'เพิ่มในลิสต์';
                btn.classList.remove('bg-red-600', 'hover:bg-red-700');
                btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                showToast('ลบออกจากลิสต์แล้ว', true);
            }
        } else {
            alert('เกิดข้อผิดพลาด');
        }
    } catch (error) {
        alert('เกิดข้อผิดพลาด');
    }
}

// JavaScript สำหรับแก้ไขรีวิว
function openEditReviewModal(reviewId, currentRating, currentReview) {
    document.getElementById('edit-review-modal').classList.remove('hidden');
    
    // ตั้งค่าค่าเดิมใน modal
    document.getElementById('edit-review-rating').value = currentRating;
    document.getElementById('edit-review-text').value = currentReview;
    
    // ตั้งค่า rating stars
    updateStars(currentRating);
    
    // ตั้งค่า action ของ form ให้ส่งไปที่ route แก้ไข
    const form = document.getElementById('edit-review-form');
    form.setAttribute('action', `/anime/{{ $anime->id }}/reviews`);
}

function updateStars(rating) {
    const stars = document.querySelectorAll('#edit-rating-stars .star-icon');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.textContent = '★';  // Filled star
            star.className = 'star-icon text-yellow-500 cursor-pointer';
        } else {
            star.textContent = '☆';  // Empty star
            star.className = 'star-icon text-gray-400 hover:text-yellow-500 cursor-pointer';
        }
    });
}

// จัดการการคลิกที่ดาวใน modal
document.querySelectorAll('#edit-rating-stars .star-icon').forEach((star, index) => {
    star.addEventListener('click', function() {
        const rating = index + 1;
        document.getElementById('edit-review-rating').value = rating;
        updateStars(rating);
    });
});

// ปิด modal เมื่อคลิกปุ่มปิดหรือพื้นหลัง
function closeEditReviewModal() {
    document.getElementById('edit-review-modal').classList.add('hidden');
}

// เพิ่ม CSRF token ให้กับ form แก้ไข
document.getElementById('edit-review-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('_method', 'PUT'); // เพิ่ม method override
    
    fetch(this.getAttribute('action'), {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            window.location.reload(); // โหลดหน้าใหม่เพื่อแสดงผล
        } else {
            alert('เกิดข้อผิดพลาดในการแก้ไขรีวิว');
        }
    })
    .catch(error => {
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
    });
});

</script>

        <!-- Modal แก้ไขรีวิว -->
        <div id="edit-review-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white">แก้ไขรีวิวของคุณ</h3>
                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeEditReviewModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="edit-review-form" method="POST">
                    @csrf
                    <input type="hidden" name="rating" id="edit-review-rating" value="5">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2">ให้คะแนน</label>
                        <div id="edit-rating-stars" class="flex space-x-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star-icon text-gray-400 hover:text-yellow-500 cursor-pointer text-2xl">☆</span>
                            @endfor
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-review-text" class="block text-gray-700 dark:text-gray-300 mb-2">รีวิว</label>
                        <textarea id="edit-review-text" name="review" rows="4" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition" placeholder="เขียนรีวิวของคุณที่นี่..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 font-medium" onclick="closeEditReviewModal()">
                            ยกเลิก
                        </button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                            บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="text-center text-gray-600">
                    <p>&copy; 2025 AnimeHub. สงวนลิขสิทธิ์ทั้งหมด</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>