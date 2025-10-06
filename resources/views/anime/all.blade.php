<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>อนิเมะทั้งหมด - AnimeHub</title>
    
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
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">อนิเมะทั้งหมด</h1>
            </div>
            
            <!-- Grid of all anime - 4 columns on large screens, 2 on medium, 1 on small -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($animes as $anime)
                    <div class="anime-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                        <div class="relative">
                            @if($anime->image_url)
                                <img src="{{ asset($anime->image_url) }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover" />
                            @else
                                <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed w-full h-48 flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400">No Image</span>
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
                                    {{ Str::limit($anime->description, 100) }}
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
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400 text-lg">ไม่พบอนิเมะ</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $animes->links() }}
            </div>
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
</body>
</html>