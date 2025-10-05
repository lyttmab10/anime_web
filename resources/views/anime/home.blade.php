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
                    <h1 class="text-3xl font-bold text-indigo-700">AnimeHub</h1>
                    <nav class="flex items-center space-x-4">
                        <ul class="flex space-x-4 mr-6">
                            <li><a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600 font-medium">หน้าแรก</a></li>
                            <li><a href="{{ route('search.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium">ค้นหา</a></li>
                            <li><a href="#listings-section" class="text-gray-700 hover:text-indigo-600 font-medium">อนิเมะทั้งหมด</a></li>
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
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">แดชบอร์ด</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">โปรไฟล์</a>
                                    <a href="{{ route('watchlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ลิสต์ของฉัน</a>
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
                <h1 class="text-4xl font-bold text-gray-800 mb-4">ค้นหาอนิเมะเรื่องใหม่ของคุณ</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    รับคำแนะนำอนิเมะส่วนตัวตามความชอบของคุณและค้นพบซีรีส์ยอดนิยม
                </p>
            </section>

            <!-- Featured Anime Section -->
            <section class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">อนิเมะแห่งวัน</h2>
                    <button id="refreshRecommendation" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        สุ่มใหม่
                    </button>
                </div>
                
                <!-- Single featured anime -->
                @if($featuredAnime)
                    <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden max-w-4xl mx-auto">
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
                                <div class="flex items-center mb-4">
                                    <span class="text-yellow-500 mr-1">★</span>
                                    <span class="font-bold">{{ $featuredAnime->rating }}/10</span>
                                    @if($featuredAnime->release_date)
                                        <span class="mx-3">•</span>
                                        <span>{{ $featuredAnime->release_date->format('Y') }}</span>
                                    @endif
                                    @if($featuredAnime->is_trending)
                                        <span class="ml-3 bg-red-500 text-white text-xs px-2 py-1 rounded">HOT</span>
                                    @endif
                                </div>
                                <a href="{{ route('anime.show', $featuredAnime->id) }}" class="bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700 transition inline-block">
                                    ดูรายละเอียด
                                </a>
                            </div>
                        </div>
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
                    <h2 class="text-2xl font-bold text-gray-800">มาแรงและเพิ่งออกใหม่</h2>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">ดูทั้งหมด</a>
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
                                @if($anime->is_trending)
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
                                    <a href="{{ route('anime.show', $anime->id) }}" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition">รายละเอียด</a>
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
                    <h2 class="text-2xl font-bold text-gray-800">รายการอนิเมะ</h2>
                    <a href="{{ route('anime.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">ดูทั้งหมด</a>
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
                                @if($anime->is_trending)
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
                                    <a href="{{ route('anime.show', $anime->id) }}" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 transition">รายละเอียด</a>
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
            <section>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">ค้นหาตามหมวดหมู่</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="category-card bg-white rounded-lg p-6 text-center shadow-md">
                        <div class="bg-indigo-100 text-indigo-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-2 text-gray-800">แอคชั่น</h3>
                        <p class="text-gray-600">25 ซีรีส์</p>
                    </div>
                    
                    <div class="category-card bg-white rounded-lg p-6 text-center shadow-md">
                        <div class="bg-pink-100 text-pink-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-2 text-gray-800">โรแมนติก</h3>
                        <p class="text-gray-600">18 ซีรีส์</p>
                    </div>
                    
                    <div class="category-card bg-white rounded-lg p-6 text-center shadow-md">
                        <div class="bg-green-100 text-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-2 text-gray-800">ผจญภัย</h3>
                        <p class="text-gray-600">22 ซีรีส์</p>
                    </div>
                    
                    <div class="category-card bg-white rounded-lg p-6 text-center shadow-md">
                        <div class="bg-yellow-100 text-yellow-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-2 text-gray-800">คอมเมดี้</h3>
                        <p class="text-gray-600">30 ซีรีส์</p>
                    </div>
                </div>
            </section>
            
            <!-- Compare Section -->
            <section class="mt-12 text-center">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-8 text-white">
                    <h2 class="text-2xl font-bold mb-4">เปรียบเทียบอนิเมะที่คุณชื่นชอบ</h2>
                    <p class="mb-6 max-w-2xl mx-auto">เปรียบเทียบคุณสมบัติของอนิเมะต่างๆ เพื่อหาเรื่องที่ใช่ที่สุดสำหรับคุณ</p>
                    <a href="{{ route('anime.compare.form') }}" class="bg-white text-indigo-600 font-bold py-3 px-8 rounded-lg shadow hover:bg-gray-100 transition inline-block">
                        เริ่มเปรียบเทียบ
                    </a>
                </div>
            </section>
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