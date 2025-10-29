<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขอนิเมะ - AnimeHub Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #f5f5f5;
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
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">ออกจากระบบ</button>
                                </form>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">แก้ไขอนิเมะ: {{ $anime->title }}</h1>
                
                <form method="POST" action="{{ route('admin.update', $anime) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-gray-700 dark:text-gray-300 mb-2">ชื่ออนิเมะ <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title', $anime->title) }}" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition" required>
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="rating" class="block text-gray-700 dark:text-gray-300 mb-2">เรตติ้ง (0-10)</label>
                                <input type="number" id="rating" name="rating" value="{{ old('rating', $anime->rating) }}" min="0" max="10" step="0.1" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                @error('rating')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="release_date" class="block text-gray-700 dark:text-gray-300 mb-2">วันที่ออกอากาศ</label>
                                <input type="date" id="release_date" name="release_date" value="{{ old('release_date', $anime->release_date) }}" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                @error('release_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="studio" class="block text-gray-700 dark:text-gray-300 mb-2">สตูดิโอ</label>
                                <input type="text" id="studio" name="studio" value="{{ old('studio', $anime->studio) }}" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                @error('studio')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="season" class="block text-gray-700 dark:text-gray-300 mb-2">ซีซั่น</label>
                                <input type="number" id="season" name="season" value="{{ old('season', $anime->season) }}" min="1" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                @error('season')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="episodes" class="block text-gray-700 dark:text-gray-300 mb-2">จำนวนตอน</label>
                                <input type="number" id="episodes" name="episodes" value="{{ old('episodes', $anime->episodes) }}" min="1" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                @error('episodes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="status" class="block text-gray-700 dark:text-gray-300 mb-2">สถานะ</label>
                                <select id="status" name="status" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                    <option value="">เลือกสถานะ</option>
                                    <option value="currently_airing" {{ old('status', $anime->status) == 'currently_airing' ? 'selected' : '' }}>กำลังออกอากาศ</option>
                                    <option value="finished_airing" {{ old('status', $anime->status) == 'finished_airing' ? 'selected' : '' }}>จบแล้ว</option>
                                    <option value="not_yet_aired" {{ old('status', $anime->status) == 'not_yet_aired' ? 'selected' : '' }}>ยังไม่เริ่มออกอากาศ</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="image" class="block text-gray-700 dark:text-gray-300 mb-2">รูปภาพ</label>
                                <input type="file" id="image" name="image" accept="image/*" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                <p class="text-gray-500 text-sm mt-1">รองรับไฟล์ JPG, PNG, GIF สูงสุด 2MB</p>
                                @if($anime->image_url)
                                    <div class="mt-2">
                                        <p class="text-gray-600 text-sm">รูปภาพปัจจุบัน:</p>
                                        <img src="{{ asset($anime->image_url) }}" alt="Current image" class="w-32 h-32 object-cover mt-1 rounded">
                                    </div>
                                @endif
                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="trailer_url" class="block text-gray-700 dark:text-gray-300 mb-2">URL ตัวอย่าง</label>
                                <input type="url" id="trailer_url" name="trailer_url" value="{{ old('trailer_url', $anime->trailer_url) }}" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                @error('trailer_url')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="official_site" class="block text-gray-700 dark:text-gray-300 mb-2">เว็บไซต์ทางการ</label>
                                <input type="url" id="official_site" name="official_site" value="{{ old('official_site', $anime->official_site) }}" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">
                                @error('official_site')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="description" class="block text-gray-700 dark:text-gray-300 mb-2">คำอธิบาย</label>
                            <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">{{ old('description', $anime->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-6">
                            <label for="genres" class="block text-gray-700 dark:text-gray-300 mb-2">แนว (Genre)</label>
                            <textarea id="genres" name="genres" rows="2" placeholder="กรอกแนวของอนิเมะ คั่นด้วยเครื่องหมายจุลภาค (เช่น Action, Adventure, Comedy)" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">{{ is_array(old('genres', $anime->genres)) ? implode(', ', old('genres', $anime->genres)) : old('genres', $anime->genres) }}</textarea>
                            @error('genres')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-6">
                            <label for="characters" class="block text-gray-700 dark:text-gray-300 mb-2">ตัวละครหลัก</label>
                            <textarea id="characters" name="characters" rows="2" placeholder="กรอกชื่อตัวละครหลัก คั่นด้วยเครื่องหมายจุลภาค (เช่น Tanjiro, Nezuko, Zenitsu)" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white transition">{{ is_array(old('characters', $anime->characters)) ? implode(', ', old('characters', $anime->characters)) : old('characters', $anime->characters) }}</textarea>
                            @error('characters')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">ยกเลิก</a>
                        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            บันทึกการเปลี่ยนแปลง
                        </button>
                    </div>
                </form>
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
    
    <script>
        // Function to handle comma-separated values for genres and characters
        document.addEventListener('DOMContentLoaded', function() {
            const genresTextarea = document.getElementById('genres');
            const charactersTextarea = document.getElementById('characters');
            
            // Process genres input - convert comma-separated to JSON format
            const processInput = function(textarea) {
                if (!textarea) return;
                
                textarea.addEventListener('blur', function() {
                    const value = this.value.trim();
                    if (value) {
                        // Split by comma and trim whitespace
                        const items = value.split(',').map(item => item.trim()).filter(item => item);
                        // Update the value back to comma-separated format
                        this.value = items.join(', ');
                    }
                });
            };
            
            processInput(genresTextarea);
            processInput(charactersTextarea);
        });
    </script>
</body>
</html>