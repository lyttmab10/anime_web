<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ลิสต์อนิเมะของคุณ - AnimeHub</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
            transform: translateY(-5px);
            box-shadow: 0 12px 15px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-watching { background-color: #34d399; color: white; }
        .status-completed { background-color: #60a5fa; color: white; }
        .status-planned { background-color: #fbbf24; color: white; }
        .status-on_hold { background-color: #a78bfa; color: white; }
        .status-dropped { background-color: #ef4444; color: white; }
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
                            <li><a href="{{ route('watchlist.index') }}" class="text-indigo-700 font-medium bg-indigo-100 px-3 py-1 rounded">ลิสต์ของฉัน</a></li>
                            <li><a href="#" class="text-gray-700 hover:text-indigo-600 font-medium">เรตติ้งสูงสุด</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">ลิสต์อนิเมะของคุณ</h1>
            
            <!-- Tabs for different statuses -->
            <div class="flex border-b border-gray-200 mb-8">
                <button class="tab-button px-4 py-2 font-medium text-gray-600 hover:text-indigo-600 border-b-2 border-transparent hover:border-indigo-500" data-tab="watching">
                    กำลังดู <span class="bg-gray-200 text-gray-700 rounded-full px-2 py-1 text-xs ml-2">{{ $groupedWatchlists->get('watching', collect())->count() }}</span>
                </button>
                <button class="tab-button px-4 py-2 font-medium text-gray-600 hover:text-indigo-600 border-b-2 border-transparent hover:border-indigo-500" data-tab="completed">
                    ดูแล้ว <span class="bg-gray-200 text-gray-700 rounded-full px-2 py-1 text-xs ml-2">{{ $groupedWatchlists->get('completed', collect())->count() }}</span>
                </button>
                <button class="tab-button px-4 py-2 font-medium text-gray-600 hover:text-indigo-600 border-b-2 border-transparent hover:border-indigo-500" data-tab="planned">
                    อยากดู <span class="bg-gray-200 text-gray-700 rounded-full px-2 py-1 text-xs ml-2">{{ $groupedWatchlists->get('planned', collect())->count() }}</span>
                </button>
                <button class="tab-button px-4 py-2 font-medium text-gray-600 hover:text-indigo-600 border-b-2 border-transparent hover:border-indigo-500" data-tab="on_hold">
                    พักดูก่อน <span class="bg-gray-200 text-gray-700 rounded-full px-2 py-1 text-xs ml-2">{{ $groupedWatchlists->get('on_hold', collect())->count() }}</span>
                </button>
                <button class="tab-button px-4 py-2 font-medium text-gray-600 hover:text-indigo-600 border-b-2 border-transparent hover:border-indigo-500" data-tab="dropped">
                     dropped <span class="bg-gray-200 text-gray-700 rounded-full px-2 py-1 text-xs ml-2">{{ $groupedWatchlists->get('dropped', collect())->count() }}</span>
                </button>
            </div>
            
            <!-- Watchlist Sections -->
            <div id="watching-section" class="watchlist-section">
                @if($groupedWatchlists->get('watching', collect())->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($groupedWatchlists->get('watching', collect()) as $watchlist)
                        @php
                            $anime = $watchlist->anime;
                        @endphp
                        <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="relative">
                                @if($anime->image_url)
                                    <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover" />
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 status-badge status-watching">กำลังดู</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}">
                                    <a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a>
                                </h3>
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-500 mr-1">★</span>
                                    <span>{{ $anime->rating }}/10</span>
                                </div>
                                
                                <!-- Progress bar -->
                                <div class="mb-3">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>ความคืบหน้า</span>
                                        <span>{{ $watchlist->progress }}/{{ $anime->episodes ?: '?' }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $anime->episodes ? ($watchlist->progress / $anime->episodes) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button class="update-status-btn bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded hover:bg-indigo-200" data-id="{{ $watchlist->id }}" data-status="completed">ดูจบแล้ว</button>
                                    <button class="update-status-btn bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded hover:bg-gray-200" data-id="{{ $watchlist->id }}" data-status="on_hold">พักดูก่อน</button>
                                </div>
                                
                                @if($watchlist->notes)
                                    <p class="mt-3 text-sm text-gray-600 italic">หมายเหตุ: {{ $watchlist->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600">คุณยังไม่มีอนิเมะที่กำลังดูอยู่</p>
                        <a href="{{ route('search.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mt-4 inline-block">ค้นหาอนิเมะที่น่าสนใจ</a>
                    </div>
                @endif
            </div>
            
            <div id="completed-section" class="watchlist-section hidden">
                @if($groupedWatchlists->get('completed', collect())->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($groupedWatchlists->get('completed', collect()) as $watchlist)
                        @php
                            $anime = $watchlist->anime;
                        @endphp
                        <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="relative">
                                @if($anime->image_url)
                                    <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover" />
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 status-badge status-completed">ดูแล้ว</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}">
                                    <a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a>
                                </h3>
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-500 mr-1">★</span>
                                    <span>{{ $anime->rating }}/10</span>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button class="update-status-btn bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded hover:bg-indigo-200" data-id="{{ $watchlist->id }}" data-status="watching">ดูอีกครั้ง</button>
                                    <button class="remove-btn bg-red-100 text-red-700 text-xs px-3 py-1 rounded hover:bg-red-200" data-id="{{ $watchlist->id }}">ลบออก</button>
                                </div>
                                
                                @if($watchlist->notes)
                                    <p class="mt-3 text-sm text-gray-600 italic">หมายเหตุ: {{ $watchlist->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600">คุณยังไม่มีอนิเมะที่ดูจบแล้ว</p>
                        <a href="{{ route('search.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mt-4 inline-block">ค้นหาอนิเมะที่น่าสนใจ</a>
                    </div>
                @endif
            </div>
            
            <div id="planned-section" class="watchlist-section hidden">
                @if($groupedWatchlists->get('planned', collect())->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($groupedWatchlists->get('planned', collect()) as $watchlist)
                        @php
                            $anime = $watchlist->anime;
                        @endphp
                        <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="relative">
                                @if($anime->image_url)
                                    <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover" />
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 status-badge status-planned">อยากดู</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}">
                                    <a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a>
                                </h3>
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-500 mr-1">★</span>
                                    <span>{{ $anime->rating }}/10</span>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button class="update-status-btn bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded hover:bg-indigo-200" data-id="{{ $watchlist->id }}" data-status="watching">เริ่มดู</button>
                                    <button class="remove-btn bg-red-100 text-red-700 text-xs px-3 py-1 rounded hover:bg-red-200" data-id="{{ $watchlist->id }}">ลบออก</button>
                                </div>
                                
                                @if($watchlist->notes)
                                    <p class="mt-3 text-sm text-gray-600 italic">หมายเหตุ: {{ $watchlist->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600">คุณยังไม่มีอนิเมะที่อยากดู</p>
                        <a href="{{ route('search.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mt-4 inline-block">ค้นหาอนิเมะที่น่าสนใจ</a>
                    </div>
                @endif
            </div>
            
            <div id="on_hold-section" class="watchlist-section hidden">
                @if($groupedWatchlists->get('on_hold', collect())->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($groupedWatchlists->get('on_hold', collect()) as $watchlist)
                        @php
                            $anime = $watchlist->anime;
                        @endphp
                        <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="relative">
                                @if($anime->image_url)
                                    <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover" />
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 status-badge status-on_hold">พักดูก่อน</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}">
                                    <a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a>
                                </h3>
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-500 mr-1">★</span>
                                    <span>{{ $anime->rating }}/10</span>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button class="update-status-btn bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded hover:bg-indigo-200" data-id="{{ $watchlist->id }}" data-status="watching">กลับมาดูต่อ</button>
                                    <button class="remove-btn bg-red-100 text-red-700 text-xs px-3 py-1 rounded hover:bg-red-200" data-id="{{ $watchlist->id }}">ลบออก</button>
                                </div>
                                
                                @if($watchlist->notes)
                                    <p class="mt-3 text-sm text-gray-600 italic">หมายเหตุ: {{ $watchlist->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600">คุณยังไม่มีอนิเมะที่พักดูก่อน</p>
                        <a href="{{ route('search.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mt-4 inline-block">ค้นหาอนิเมะที่น่าสนใจ</a>
                    </div>
                @endif
            </div>
            
            <div id="dropped-section" class="watchlist-section hidden">
                @if($groupedWatchlists->get('dropped', collect())->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($groupedWatchlists->get('dropped', collect()) as $watchlist)
                        @php
                            $anime = $watchlist->anime;
                        @endphp
                        <div class="anime-card bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="relative">
                                @if($anime->image_url)
                                    <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-48 object-cover" />
                                @else
                                    <div class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                                <span class="absolute top-2 right-2 status-badge status-dropped">dropped</span>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}">
                                    <a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a>
                                </h3>
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-500 mr-1">★</span>
                                    <span>{{ $anime->rating }}/10</span>
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button class="update-status-btn bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded hover:bg-indigo-200" data-id="{{ $watchlist->id }}" data-status="watching">ลองดูอีกครั้ง</button>
                                    <button class="remove-btn bg-red-100 text-red-700 text-xs px-3 py-1 rounded hover:bg-red-200" data-id="{{ $watchlist->id }}">ลบออก</button>
                                </div>
                                
                                @if($watchlist->notes)
                                    <p class="mt-3 text-sm text-gray-600 italic">หมายเหตุ: {{ $watchlist->notes }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600">คุณยังไม่มีอนิเมะที่ dropped</p>
                        <a href="{{ route('search.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mt-4 inline-block">ค้นหาอนิเมะที่น่าสนใจ</a>
                    </div>
                @endif
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
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tab switching functionality
                const tabButtons = document.querySelectorAll('.tab-button');
                const watchlistSections = document.querySelectorAll('.watchlist-section');
                
                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const tab = this.getAttribute('data-tab');
                        
                        // Update active button
                        tabButtons.forEach(btn => {
                            btn.classList.remove('border-indigo-500', 'text-indigo-600');
                            btn.classList.add('text-gray-600', 'border-transparent');
                        });
                        this.classList.remove('text-gray-600', 'border-transparent');
                        this.classList.add('border-indigo-500', 'text-indigo-600');
                        
                        // Show selected section
                        watchlistSections.forEach(section => {
                            section.classList.add('hidden');
                        });
                        document.getElementById(tab + '-section').classList.remove('hidden');
                    });
                });
                
                // Update status buttons
                const updateStatusButtons = document.querySelectorAll('.update-status-btn');
                updateStatusButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const watchlistId = this.getAttribute('data-id');
                        const newStatus = this.getAttribute('data-status');
                        
                        fetch(`/watchlist/${watchlistId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                // Reload the page to update the display
                                location.reload();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });
                });
                
                // Remove buttons
                const removeButtons = document.querySelectorAll('.remove-btn');
                removeButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const watchlistId = this.getAttribute('data-id');
                        
                        if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบอนิเมะนี้ออกจากลิสต์?')) {
                            fetch(`/watchlist/${watchlistId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    // Reload the page to update the display
                                    location.reload();
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    });
                });
            });
        </script>
    </body>
</html>