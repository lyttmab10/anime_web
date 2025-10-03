<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ค้นหาอนิเมะ - AnimeHub</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="container mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-indigo-700">AnimeHub</h1>
                    <nav>
                        <ul class="flex space-x-6">
                            <li><a href="/" class="text-gray-700 hover:text-indigo-600 font-medium">หน้าแรก</a></li>
                            <li><a href="{{ route('search.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium">ค้นหา</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-indigo-600 font-medium">หมวดหมู่</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-indigo-600 font-medium">เรตติ้งสูงสุด</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <!-- Search Form -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">ค้นหาอนิเมะ</h2>
                
                <form method="GET" action="{{ route('search.index') }}" class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="lg:col-span-2">
                            <input 
                                type="text" 
                                name="q" 
                                placeholder="ค้นหาจากชื่ออนิเมะ, สตูดิโอ หรือตัวละคร..." 
                                value="{{ request('q') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            >
                        </div>
                        <div>
                            <select name="genre" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">ทุกแนว</option>
                                @foreach($genres as $g)
                                <option value="{{ $g }}" {{ request('genre') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">ทุกปี</option>
                                @foreach($years as $y)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                ค้นหา
                            </button>
                        </div>
                    </div>
                    
                    <!-- Additional Filters -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <select name="season" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">ทุกซีซั่น</option>
                                @foreach($seasons as $s)
                                <option value="{{ $s }}" {{ request('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="studio" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">ทุกสตูดิโอ</option>
                                @foreach($studios as $s)
                                <option value="{{ $s }}" {{ request('studio') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
                
                <!-- Search Results Info -->
                <div class="mb-6">
                    <p class="text-gray-600">
                        พบ {{ $animes->total() }} ผลลัพธ์
                        @if(request('q') || request('genre') || request('year') || request('season') || request('studio'))
                            @if(request('q'))
                                สำหรับ "{{ request('q') }}"
                            @endif
                            @if(request('genre'))
                                แนว {{ request('genre') }}
                            @endif
                            @if(request('year'))
                                ปี {{ request('year') }}
                            @endif
                            @if(request('season'))
                                ซีซั่น {{ request('season') }}
                            @endif
                            @if(request('studio'))
                                สตูดิโอ {{ request('studio') }}
                            @endif
                        @endif
                    </p>
                </div>
            </section>

            <!-- Search Results -->
            <section>
                @if($animes->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($animes as $anime)
                            <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                                <div class="relative">
                                    @if($anime->image_url)
                                        <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover rounded-t-lg" />
                                    @else
                                        <div class="bg-gray-200 border-2 border-dashed rounded-t-lg w-full h-48 flex items-center justify-center">
                                            <span class="text-gray-500">No Image</span>
                                        </div>
                                    @endif
                                    @if($anime->is_trending)
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">มาแรง</span>
                                    @endif
                                </div>
                                <div class="p-4 flex-grow">
                                    <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}">
                                        <a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a>
                                    </h3>
                                    <div class="flex items-center mb-2">
                                        <span class="text-yellow-500 mr-1">★</span>
                                        <span>{{ $anime->rating }}/10</span>
                                        @if($anime->release_date)
                                            <span class="mx-2">•</span>
                                            <span>{{ $anime->release_date->format('Y') }}</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 text-sm mb-2 truncate" title="{{ $anime->studio }}">
                                        สตูดิโอ: {{ $anime->studio ?: 'ไม่ระบุ' }}
                                    </p>
                                    @if($anime->genres && is_array($anime->genres))
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @foreach(array_slice($anime->genres, 0, 3) as $genre)
                                                <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">{{ $genre }}</span>
                                            @endforeach
                                            @if(count($anime->genres) > 3)
                                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">+{{ count($anime->genres) - 3 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $animes->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <h3 class="text-xl font-medium text-gray-800 mb-2">ไม่พบอนิเมะที่คุณกำลังมองหา</h3>
                        <p class="text-gray-600">กรุณาลองค้นหาด้วยคำอื่น หรือเปลี่ยนตัวกรอง</p>
                    </div>
                @endif
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
</body>
</html>