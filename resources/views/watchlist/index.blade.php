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
            <h1 class="text-3xl font-bold text-gray-800 mb-8">ลิสต์ของฉัน</h1>
            
            <!-- All Watchlist Items -->
            @if($watchlists->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($watchlists as $watchlist)
                    @php
                        $anime = $watchlist->anime;
                        // ตั้งค่า status badge class
                        $statusClass = match($watchlist->status) {
                            'watching' => 'status-watching',
                            'completed' => 'status-completed',
                            'planned' => 'status-planned',
                            'on_hold' => 'status-on_hold',
                            'dropped' => 'status-dropped',
                            default => 'status-watching'
                        };
                        $statusText = match($watchlist->status) {
                            'watching' => 'กำลังดู',
                            'completed' => 'ดูแล้ว',
                            'planned' => 'อยากดู',
                            'on_hold' => 'พักดูก่อน',
                            'dropped' => 'dropped',
                            default => 'กำลังดู'
                        };
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
                            <span class="absolute top-2 right-2 status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}">
                                <a href="{{ route('anime.show', $anime->id) }}" class="hover:text-indigo-600">{{ $anime->title }}</a>
                            </h3>
                            <div class="flex items-center mb-2">
                                <span class="text-yellow-500 mr-1">★</span>
                                <span>{{ $anime->rating }}/10</span>
                            </div>
                            
                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">{{ $anime->description ?: 'ไม่มีข้อมูลเรื่องย่อ' }}</p>
                            
                            <div class="flex space-x-2">
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
                    <p class="text-gray-600">คุณยังไม่มีอนิเมะอยู่ในลิสต์ของคุณ</p>
                    <a href="{{ route('search.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium mt-4 inline-block">ค้นหาอนิเมะที่น่าสนใจ</a>
                </div>
            @endif
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