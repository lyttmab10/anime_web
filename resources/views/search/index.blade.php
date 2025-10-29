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
        .genre-btn {
            transition: all 0.2s ease;
        }
        .genre-btn:hover {
            transform: translateY(-2px);
        }
        .genre-btn.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
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
                            <li><a href="{{ route('search.index') }}" class="text-indigo-700 font-medium bg-indigo-100 px-3 py-1 rounded">ค้นหา</a></li>
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
            <!-- Search Form -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">ค้นหาอนิเมะ</h2>
                
                <form method="GET" action="/search" class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
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
                            <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">ทุกปี</option>
                                @if(isset($years))
                                    @foreach($years as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <button type="button" onclick="resetSearch()" class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                                รีเซ็ต
                            </button>
                        </div>
                    </div>
                    
                    <!-- Additional Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <select name="season" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">ทุกซีซั่น</option>
                                @if(isset($seasons))
                                    @foreach($seasons as $s)
                                    <option value="{{ $s }}" {{ request('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <select name="studio" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">ทุกสตูดิโอ</option>
                                @if(isset($studios))
                                    @foreach($studios as $s)
                                    <option value="{{ $s }}" {{ request('studio') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <!-- Genre Filter as Individual Buttons -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">หมวดหมู่:</h3>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" 
                                    onclick="toggleGenre('')"
                                    class="genre-btn px-3 py-1 border rounded-full text-sm {{ empty(request('genre')) && empty(request('genres')) ? 'active' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                                ทุกแนว
                            </button>
                            @if(isset($allGenres))
                                @foreach($allGenres as $g)
                                    <button type="button" 
                                            onclick="toggleGenre('{{ addslashes($g) }}')"
                                            class="genre-btn px-3 py-1 border rounded-full text-sm {{ (request('genre') == $g || (is_array(request('genres')) && in_array($g, request('genres')))) ? 'active' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                                        {{ $g }}
                                    </button>
                                @endforeach
                            @endif
                        </div>
                        <!-- Hidden input to store selected genres -->
                        <input type="hidden" name="genres" id="selectedGenresInput" value="{{ request('genres') ? implode(',', request('genres')) : request('genre', '') }}">
                    </div>
                </form>
                
                <!-- Search Results Info -->
                <div class="mb-6">
                    <p class="text-gray-600">
                        @if(isset($animes))
                            พบ {{ $animes->total() }} ผลลัพธ์
                            @if(request('q') || request('genre') || request('year') || request('season') || request('studio'))
                                @if(request('q'))
                                    สำหรับ "{{ request('q') }}"
                                @endif
                                @if(request('genre'))
                                    แนว {{ request('genre') }}
                                @endif
                                @if(request('genres') && is_array(request('genres')))
                                    @foreach(request('genres') as $selectedGenre)
                                        แนว {{ $selectedGenre }}
                                        @if(!$loop->last), @endif
                                    @endforeach
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
                        @endif
                    </p>
                </div>
            </section>

            <!-- Search Results -->
            <section>
                @if(isset($animes) && $animes->count() > 0)
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
                                    @if($anime->rating >= 9.0)
                                        <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">มาแรง</span>
                                    @endif
                                </div>
                                <div class="p-4 flex-grow">
                                    <h3 class="font-bold text-lg mb-1 truncate" title="{{ $anime->title }}">
                                        <a href="/anime/{{ $anime->id }}" class="hover:text-indigo-600">{{ $anime->title }}</a>
                                    </h3>
                                    <div class="flex items-center mb-2">
                                        <span class="text-yellow-500 mr-1">★</span>
                                        <span>{{ $anime->rating }}/10</span>
                                        @if($anime->release_date && $anime->release_date instanceof \Carbon\Carbon)
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
                        @if(isset($animes))
                            {{ $animes->appends(request()->query())->links() }}
                        @endif
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
    
    <script>
        function toggleGenre(genre) {
            // Get current selected genres from hidden input
            const selectedGenresInput = document.getElementById('selectedGenresInput');
            let selectedGenres = selectedGenresInput.value ? selectedGenresInput.value.split(',') : [];
            
            // Remove empty values
            selectedGenres = selectedGenres.filter(g => g.trim() !== '');
            
            if (genre === '') {
                // If 'ทุกแนว' is clicked, clear all selections
                selectedGenres = [];
            } else {
                // Toggle the genre in the array
                const index = selectedGenres.indexOf(genre);
                if (index > -1) {
                    // Remove if already selected
                    selectedGenres.splice(index, 1);
                } else {
                    // Add if not selected
                    selectedGenres.push(genre);
                }
            }
            
            // Update hidden input
            selectedGenresInput.value = selectedGenres.join(',');
            
            // Update active states for genre buttons
            updateGenreButtonStates(selectedGenres);
            
            // Submit the form with updated genres
            submitSearchForm();
        }
        
        function updateGenreButtonStates(selectedGenres) {
            // Remove empty values
            const filteredSelectedGenres = selectedGenres.filter(g => g.trim() !== '');
            
            // Update active states for genre buttons
            document.querySelectorAll('.genre-btn').forEach(btn => {
                const genre = btn.getAttribute('onclick')?.match(/'([^']*)'/)?.[1] || '';
                
                btn.classList.remove('active');
                btn.classList.add('bg-white', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                
                if (genre && filteredSelectedGenres.includes(genre)) {
                    btn.classList.add('active');
                    btn.classList.remove('bg-white', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                } else if (!genre && filteredSelectedGenres.length === 0) {
                    btn.classList.add('active');
                    btn.classList.remove('bg-white', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                }
            });
        }
        
        function resetSearch() {
            // Reset search input
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Reset year, season, studio selects
            const yearSelect = document.querySelector('select[name="year"]');
            const seasonSelect = document.querySelector('select[name="season"]');
            const studioSelect = document.querySelector('select[name="studio"]');
            
            if (yearSelect) {
                yearSelect.value = '';
            }
            if (seasonSelect) {
                seasonSelect.value = '';
            }
            if (studioSelect) {
                studioSelect.value = '';
            }
            
            // Reset genres
            const selectedGenresInput = document.getElementById('selectedGenresInput');
            selectedGenresInput.value = '';
            
            // Update genre button states
            updateGenreButtonStates([]);
            
            // Submit the form to refresh results
            const form = document.querySelector('form[method="GET"]');
            form.submit();
        }
        
        let searchTimeout;
        
        function submitSearchForm() {
            // Clear any existing timeout to debounce the search
            clearTimeout(searchTimeout);
            
            // Set a timeout to delay the search so it doesn't fire on every keystroke
            searchTimeout = setTimeout(() => {
                const form = document.querySelector('form[method="GET"]');
                const selectedGenresInput = document.getElementById('selectedGenresInput');
                const selectedGenres = selectedGenresInput.value ? selectedGenresInput.value.split(',') : [];
                
                // Remove any existing genre parameters from the form
                const existingGenreInputs = form.querySelectorAll('input[name="genres[]"], input[name="genre"]');
                existingGenreInputs.forEach(input => input.remove());
                
                // Add selected genres as hidden inputs
                selectedGenres.forEach(genre => {
                    if (genre.trim() !== '') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'genres[]';
                        input.value = genre;
                        form.appendChild(input);
                    }
                });
                
                form.submit();
            }, 500); // Wait 500ms before submitting to debounce the search
        }
        
        // Initialize active states when page loads and set up event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const selectedGenresInput = document.getElementById('selectedGenresInput');
            const selectedGenres = selectedGenresInput.value ? selectedGenresInput.value.split(',') : [];
            
            // Remove empty values
            const filteredSelectedGenres = selectedGenres.filter(g => g.trim() !== '');
            
            // Update active states for genre buttons
            document.querySelectorAll('.genre-btn').forEach(btn => {
                const genre = btn.getAttribute('onclick')?.match(/'([^']*)'/)?.[1] || '';
                if (genre && filteredSelectedGenres.includes(genre)) {
                    btn.classList.add('active');
                    btn.classList.remove('bg-white', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                } else if (!genre && filteredSelectedGenres.length === 0) {
                    btn.classList.add('active');
                    btn.classList.remove('bg-white', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                }
            });
            
            // Add event listeners to the search input fields to enable instant search
            const searchInput = document.querySelector('input[name="q"]');
            const yearSelect = document.querySelector('select[name="year"]');
            const seasonSelect = document.querySelector('select[name="season"]');
            const studioSelect = document.querySelector('select[name="studio"]');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    submitSearchForm();
                });
            }
            
            if (yearSelect) {
                yearSelect.addEventListener('change', function() {
                    submitSearchForm();
                });
            }
            
            if (seasonSelect) {
                seasonSelect.addEventListener('change', function() {
                    submitSearchForm();
                });
            }
            
            if (studioSelect) {
                studioSelect.addEventListener('change', function() {
                    submitSearchForm();
                });
            }
        });
    </script>
</body>
</html>
