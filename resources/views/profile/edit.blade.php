<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>โปรไฟล์ - AnimeHub</title>
    
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">โปรไฟล์</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Profile Information -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">ข้อมูลโปรไฟล์</h2>

                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ</label>
                        <input id="name" name="name" type="text" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 transition" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">อีเมล</label>
                        <input id="email" name="email" type="email" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 transition" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2">
                                <p class="text-sm text-gray-800">
                                    ที่อยู่อีเมลของคุณยังไม่ได้รับการยืนยัน

                                    <form method="post" action="{{ route('verification.send') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            คลิกที่นี่เพื่อส่งอีเมลยืนยันใหม่
                                        </button>
                                    </form>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        ส่งลิงก์ยืนยันใหม่ไปยังที่อยู่อีเมลของคุณแล้ว
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">วันเกิด</label>
                        <input id="birth_date" name="birth_date" type="date" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 transition" value="{{ old('birth_date', $user->birth_date) }}" autocomplete="bday" />
                        @error('birth_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            บันทึก
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-gray-600">บันทึกแล้ว</p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Reviews Section -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    รีวิวที่เขียน: {{ $reviewCount }} รีวิว
                </h2>
                
                @if($user->reviews->count() > 0)
                <div class="space-y-4">
                    @foreach($user->reviews as $review)
                    <div class="border border-gray-200 rounded p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-800 text-lg">{{ $review->anime->title }}</p>
                                <p class="text-gray-600">เรตติ้ง: {{ $review->rating }}/5</p>
                                <p class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                                @if($review->review)
                                <p class="mt-2 text-gray-700">{{ $review->review }}</p>
                                @endif
                            </div>
                            <a href="{{ route('anime.show', $review->anime->id) }}" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 transition text-sm">
                                ดู
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-600">คุณยังไม่ได้เขียนรีวิวใดๆ</p>
                @endif
            </div>

            <!-- Change Password -->
            <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-2">
                <h2 class="text-xl font-bold text-gray-800 mb-4">เปลี่ยนรหัสผ่าน</h2>

                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านปัจจุบัน</label>
                        <input id="update_password_current_password" name="current_password" type="password" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 transition" autocomplete="current-password" />
                        @error('current_password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านใหม่</label>
                        <input id="update_password_password" name="password" type="password" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 transition" autocomplete="new-password" />
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่านใหม่</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 transition" autocomplete="new-password" />
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            เปลี่ยนรหัสผ่าน
                        </button>

                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-gray-600">เปลี่ยนแล้ว</p>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="bg-white p-6 rounded-lg shadow-md lg:col-span-2">
                <h2 class="text-xl font-bold text-gray-800 mb-4">ลบบัญชี</h2>
                
                <p class="text-gray-600 mb-4">
                    หลังจากลบบัญชีของคุณแล้ว ทรัพยากรทั้งหมดและข้อมูลของคุณจะถูกลบออกอย่างถาวร โปรดพิจารณาอย่างรอบคอบก่อนดำเนินการต่อ
                </p>

                <button type="button" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition" onclick="document.getElementById('delete-account-modal').classList.remove('hidden')">
                    ลบบัญชี
                </button>
            </div>
        </div>

        <!-- Delete Account Modal -->
        <div id="delete-account-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-bold text-gray-800 mb-4">คุณแน่ใจหรือไม่?</h3>
                <p class="text-gray-600 mb-6">
                    หลังจากลบบัญชีของคุณแล้ว ทรัพยากรทั้งหมดและข้อมูลของคุณจะถูกลบออกอย่างถาวร โปรดป้อนรหัสผ่านของคุณเพื่อยืนยันว่าคุณต้องการลบบัญชีของคุณอย่างถาวร
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                        <input id="password" name="password" type="password" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 transition" placeholder="Password" required autocomplete="current-password" />
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium" onclick="document.getElementById('delete-account-modal').classList.add('hidden')">
                            ยกเลิก
                        </button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                            ลบบัญชี
                        </button>
                    </div>
                </form>
            </div>
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
</body>
</html>