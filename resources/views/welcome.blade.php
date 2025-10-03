@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-4">ค้นหาอนิเมะเรื่องใหม่ของคุณ</h1>
        <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
            รับคำแนะนำอนิเมะส่วนตัวตามความชอบของคุณและค้นพบซีรีส์ยอดนิยม
        </p>
        
        <!-- Random Anime Button -->
        <div class="mt-6">
            <a href="{{ route('anime.random') }}" class="inline-block bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:from-purple-700 hover:to-indigo-700 transition transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                </svg>
                สุ่มอนิเมะให้ดู
            </a>
        </div>
    </div>

    @auth
    <!-- Featured Anime Section -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">อนิเมะแห่งวัน</h2>
            <button id="refreshRecommendation" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                สุ่มใหม่
            </button>
        </div>
        
        <!-- Single featured anime -->
        @if($featuredAnime ?? null)
            <div class="anime-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden max-w-4xl mx-auto">
                <div class="md:flex">
                    <div class="md:w-1/3">
                        <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed w-full h-64 md:h-full" />
                    </div>
                    <div class="p-6 md:w-2/3">
                        <h3 class="font-bold text-2xl mb-2 text-gray-800 dark:text-white">{{ $featuredAnime->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            @if($featuredAnime->description)
                                {{ $featuredAnime->description }}
                            @else
                                ไม่มีคำอธิบายสำหรับอนิเมะเรื่องนี้
                            @endif
                        </p>
                        <div class="flex items-center mb-4">
                            <span class="text-yellow-500 mr-1">★</span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $featuredAnime->rating }}/10</span>
                            @if($featuredAnime->release_date)
                                <span class="mx-3 text-gray-600 dark:text-gray-300">•</span>
                                <span class="text-gray-600 dark:text-gray-300">{{ $featuredAnime->release_date->format('Y') }}</span>
                            @endif
                            @if($featuredAnime->is_trending)
                                <span class="ml-3 bg-red-500 text-white text-xs px-2 py-1 rounded">HOT</span>
                            @endif
                        </div>
                        <button class="bg-indigo-600 text-white px-6 py-3 rounded hover:bg-indigo-700 transition">
                            ดูรายละเอียด
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">ไม่มีอนิเมะในขณะนี้</p>
            </div>
        @endif
    </section>

    <!-- Trending/Recently Added Section -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">มาแรงและเพิ่งออกใหม่</h2>
            <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">ดูทั้งหมด</a>
        </div>
        
        <!-- Grid of trending and recent anime - 4 columns x 5 rows -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($trendingAndRecentAnimes ?? collect() as $anime)
                <div class="anime-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                    <div class="relative">
                        @if($anime->image_url)
                            <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover rounded-t-lg" />
                        @else
                            <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed rounded-t-lg w-full h-48 flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400">No Image</span>
                            </div>
                        @endif
                        @if($anime->is_trending)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">มาแรง</span>
                        @endif
                    </div>
                    <div class="p-4 flex-grow">
                        <h3 class="font-bold text-lg mb-1 truncate text-gray-800 dark:text-white" title="{{ $anime->title }}">{{ $anime->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">
                            @if($anime->release_date)
                                {{ $anime->release_date->format('Y') }}
                            @endif
                        </p>
                        <div class="flex items-center mt-auto">
                            <span class="text-yellow-500 mr-1">★</span>
                            <span class="text-gray-800 dark:text-white">{{ $anime->rating }}/10</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">ไม่พบอนิเมะที่มาแรงหรือเพิ่งออกใหม่</p>
                </div>
            @endforelse
        </div>
    </section>
    @else
    <!-- Welcome Section for Guests -->
    <div class="max-w-3xl mx-auto text-center bg-white dark:bg-gray-800 rounded-xl shadow-md p-12 mb-12">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">ยินดีต้อนรับสู่ AnimeHub!</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-8">
            เข้าสู่ระบบหรือสมัครสมาชิกเพื่อเข้าถึงคุณสมบัติพิเศษทั้งหมดของเว็บไซต์
        </p>
        <div class="flex justify-center space-x-4">
            <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">เข้าสู่ระบบ</a>
            <a href="{{ route('register') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">สมัครสมาชิก</a>
        </div>
    </div>
    @endauth

    <!-- Categories Section -->
    <section>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">ค้นหาตามหมวดหมู่</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="category-card bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-md">
                <div class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-gray-800 dark:text-white">แอคชั่น</h3>
                <p class="text-gray-600 dark:text-gray-300">25 ซีรีส์</p>
            </div>
            
            <div class="category-card bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-md">
                <div class="bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-300 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-gray-800 dark:text-white">โรแมนติก</h3>
                <p class="text-gray-600 dark:text-gray-300">18 ซีรีส์</p>
            </div>
            
            <div class="category-card bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-md">
                <div class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-300 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-gray-800 dark:text-white">ผจญภัย</h3>
                <p class="text-gray-600 dark:text-gray-300">22 ซีรีส์</p>
            </div>
            
            <div class="category-card bg-white dark:bg-gray-800 rounded-lg p-6 text-center shadow-md">
                <div class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-300 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-gray-800 dark:text-white">คอมเมดี้</h3>
                <p class="text-gray-600 dark:text-gray-300">30 ซีรีส์</p>
            </div>
        </div>
    </section>
</div>

<script>
    document.getElementById('refreshRecommendation').addEventListener('click', function() {
        // In a real application, this would make an AJAX request to get a new random anime
        location.reload(); // For now, just refresh the page to get a new random anime
    });
</script>
@endsection