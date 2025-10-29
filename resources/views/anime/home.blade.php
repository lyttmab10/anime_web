<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แนะนำอนิเมะ - AnimeHub</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
        .category-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium mr-4">จัดการอนิเมะ</a>
                            @endif
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 hover:text-indigo-600">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">โปรไฟล์</a>
                                    <a href="{{ route('watchlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ลิสต์ของฉัน</a>
                                    @if(Auth::user()->is_admin)
                                        <a href="{{ route('admin.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">จัดการอนิเมะ</a>
                                    @endif
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
            <!-- Hero Section -->
            <section class="mb-12 text-center">
                <h1 class="text-5xl font-bold text-gray-800 mb-4">อนิเมะสำหรับ คุณ</h1>
            </section>

            <!-- Featured Anime Section -->
            <section class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-extrabold text-indigo-700 border-b-2 border-indigo-500 pb-2">สุ่มอนิเมะประจำวัน</h2>
                    <button id="refreshRecommendation" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                        สุ่มใหม่
                    </button>
                </div>
                
                <!-- Single featured anime -->
                @if($featuredAnime)
                    <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden max-w-4xl mx-auto relative">
                        <div class="md:flex">
                            <div class="md:w-1/3">
                                @if($featuredAnime->image_url)
                                    <img src="{{ asset($featuredAnime->image_url) }}" alt="{{ $featuredAnime->title }}" class="w-full h-64 md:h-full object-cover" />
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed w-full h-64 md:h-full flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6 md:w-2/3">
                                <h3 class="font-bold text-2xl mb-2">{{ $featuredAnime->title }}</h3>
                                <p class="text-gray-600 mb-4">
                                    @if($featuredAnime->description)
                                        {{ $featuredAnime->description }}
                                    @else
                                        ไม่มีคำอธิบายสำหรับอนิเมะเรื่องนี้
                                    @endif
                                </p>
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-500 mr-1">★</span>
                                    <span class="font-bold text-lg">{{ $featuredAnime->rating }}/10</span>
                                    @if($featuredAnime->release_date)
                                        <span class="mx-3">•</span>
                                        <span class="text-lg">{{ $featuredAnime->release_date->format('Y') }}</span>
                                    @endif
                                    @if($featuredAnime->rating >= 9.0)
                                        <span class="ml-3 bg-red-500 text-white text-sm px-2 py-1 rounded">HOT</span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <div class="text-sm py-2 px-3 bg-gray-100 dark:bg-gray-700 rounded">
                                        <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">สตูดิโอ:</span><br> {{ $featuredAnime->studio ?: '-' }}</p>
                                    </div>
                                    <div class="text-sm py-2 px-3 bg-gray-100 dark:bg-gray-700 rounded">
                                        <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">ตอน:</span><br> {{ $featuredAnime->episodes ?: '-' }}</p>
                                    </div>
                                    <div class="text-sm py-2 px-3 bg-gray-100 dark:bg-gray-700 rounded">
                                        <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">ซีซั่น:</span><br> {{ $featuredAnime->season ?: '-' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Genres Section -->
                                @if($featuredAnime->genres && is_array($featuredAnime->genres))
                                    <div class="mb-4">
                                        <h4 class="font-bold text-gray-700 mb-2">หมวดหมู่:</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(array_slice($featuredAnime->genres, 0, 5) as $genre)
                                                <span class="bg-indigo-100 text-indigo-800 text-sm px-3 py-1 rounded-full">{{ $genre }}</span>
                                            @endforeach
                                            @if(count($featuredAnime->genres) > 5)
                                                <span class="bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded-full">+{{ count($featuredAnime->genres) - 5 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('anime.show', $featuredAnime->id) }}" class="absolute bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 transition">
                            ดูรายละเอียด
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">ไม่มีอนิเมะในขณะนี้</p>
                    </div>
                @endif
            </section>

            <!-- Trending/Recently Added Section -->
            <section class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-extrabold text-indigo-700 border-b-2 border-indigo-500 pb-2">อนิเมะมาแรง</h2>
                    <a href="{{ route('anime.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                        ดูทั้งหมด
                    </a>
                </div>
                
                <!-- Grid of trending and recent anime - 4 columns x 5 rows -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse($trendingAndRecentAnimes as $anime)
                        <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                            <div class="relative">
                                @if($anime->image_url)
                                    <img src="{{ asset($anime->image_url) }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover" />
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
@if($anime->rating >= 9.0)
                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">มาแรง</span>
                                @endif
                            </div>
                            <div class="p-4 flex-grow flex flex-col">
                                <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}"><a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a></h3>
                                <p class="text-gray-600 text-sm mb-2">
                                    @if($anime->release_date)
                                        {{ $anime->release_date->format('Y') }}
                                    @endif
                                </p>
                                <p class="text-gray-600 text-sm mb-2 flex-grow">
                                    @if($anime->description)
                                        {{ Str::limit($anime->description, 200) }}
                                    @else
                                        ไม่มีคำอธิบาย
                                    @endif
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex items-center">
                                        <span class="text-yellow-500 mr-1">★</span>
                                        <span>{{ $anime->rating }}/10</span>
                                    </div>
                                    <a href="{{ route('anime.show', $anime->id) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">รายละเอียด</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-8">
                            <p class="text-gray-500">ไม่พบอนิเมะที่มาแรงหรือเพิ่งออกใหม่</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Anime Listings Section (30 items) -->
            <section id="listings-section" class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-extrabold text-indigo-700 border-b-2 border-indigo-500 pb-2">รายการอนิเมะ</h2>
                    <a href="{{ route('anime.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                        ดูทั้งหมด
                    </a>
                </div>
                
                <!-- Grid of 30 anime listings - 5 columns x 6 rows -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @forelse($animeListings as $anime)
                        <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                            <div class="relative">
                                @if($anime->image_url)
                                    <img src="{{ asset($anime->image_url) }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover" />
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
@if($anime->rating >= 9.0)
                                    <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">มาแรง</span>
                                @endif
                            </div>
                            <div class="p-4 flex-grow flex flex-col">
                                <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}"><a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a></h3>
                                <p class="text-gray-600 text-sm mb-2">
                                    @if($anime->release_date)
                                        {{ $anime->release_date->format('Y') }}
                                    @endif
                                </p>
                                <p class="text-gray-600 text-sm mb-2 flex-grow">
                                    @if($anime->description)
                                        {{ Str::limit($anime->description, 200) }}
                                    @else
                                        ไม่มีคำอธิบาย
                                    @endif
                                </p>
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex items-center">
                                        <span class="text-yellow-500 mr-1">★</span>
                                        <span>{{ $anime->rating }}/10</span>
                                    </div>
                                    <a href="{{ route('anime.show', $anime->id) }}" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">รายละเอียด</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-5 text-center py-8">
                            <p class="text-gray-500">ไม่พบอนิเมะ</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Categories Section -->
            <section id="categories-section">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">ค้นหาตามหมวดหมู่</h2>
                    <a href="{{ route('search.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">ดูทั้งหมด</a>
                </div>
                
                <!-- Category cards grid - showing only 4 categories -->
                <div id="category-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $selectedGenres = ['Action', 'Adventure', 'Comedy', 'Drama'];
                    @endphp
                    @foreach($selectedGenres as $genre)
                        @php
                            $animeCount = App\Models\Anime::whereJsonContains('genres', $genre)->count();
                        @endphp
                        <a href="/search?genres%5B%5D={{ urlencode($genre) }}" class="category-card bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition-shadow block">
                            <div class="bg-indigo-100 text-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-xl mb-2 text-gray-800">{{ $genre }}</h3>
                            <p class="text-gray-600">{{ $animeCount }} ซีรีส์</p>
                        </a>
                    @endforeach
                </div>
            </section>
            
            <!-- Compare Section -->

        </main>

        <!-- Footer -->
        <footer class="bg-white border-t mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="text-center text-gray-600">
                    <p>&copy; 2025 AnimeHub. สงวนลิขสิทธิ์ทั้งหมด</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.getElementById('refreshRecommendation').addEventListener('click', function() {
            // In a real application, this would make an AJAX request to get a new random anime
            location.reload(); // For now, just refresh the page to get a new random anime
        });
    </script>
</body>
</html>
