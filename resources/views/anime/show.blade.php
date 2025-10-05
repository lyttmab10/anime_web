@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Anime Detail Section -->
    <section class="mb-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3">
                    @if($anime->image_url)
                        <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-96 object-cover" />
                    @else
                        <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed w-full h-96 flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400">No Image</span>
                        </div>
                    @endif
                </div>
                <div class="p-6 md:w-2/3">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="font-bold text-3xl mb-2 text-gray-800 dark:text-white">{{ $anime->title }}</h1>
                            <div class="flex items-center mb-4">
                                <span class="text-yellow-500 mr-2 text-xl">★</span>
                                <span class="font-bold text-xl text-gray-800 dark:text-white">{{ $anime->rating }}/10</span>
                                @if($anime->is_trending)
                                    <span class="ml-4 bg-red-500 text-white text-sm px-3 py-1 rounded-full">มาแรง</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                                เพิ่มในลิสต์
                            </button>
                            <button class="bg-gray-600 dark:bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-700 dark:hover:bg-gray-600 transition">
                                ให้คะแนน
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">ปีที่ฉาย:</span> 
                                {{ $anime->release_date ? $anime->release_date->format('Y') : 'ไม่ระบุ' }}</p>
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">สตูดิโอ:</span> {{ $anime->studio ?: 'ไม่ระบุ' }}</p>
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">จำนวนตอน:</span> {{ $anime->episodes ?: 'ไม่ระบุ' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">ซีซั่น:</span> {{ $anime->season ?: 'ไม่ระบุ' }}</p>
                            <p class="text-gray-600 dark:text-gray-300"><span class="font-bold">สถานะ:</span> 
                                @if($anime->status == 'currently_airing')
                                    กำลังออกอากาศ
                                @elseif($anime->status == 'finished_airing')
                                    ฉายจบแล้ว
                                @else
                                    ยังไม่เริ่มฉาย
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($anime->genres && is_array($anime->genres))
                        <div class="mb-4">
                            <span class="font-bold text-gray-700 dark:text-gray-300">แนว:</span>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($anime->genres as $genre)
                                    <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 text-sm px-3 py-1 rounded-full">{{ $genre }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($anime->official_site)
                        <div class="mb-4">
                            <a href="{{ $anime->official_site }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                ดูข้อมูลเพิ่มเติมที่เว็บไซต์ทางการ
                            </a>
                        </div>
                    @endif
                    
                    @if($anime->trailer_url)
                        <div class="mb-4">
                            <button class="bg-red-600 dark:bg-red-700 text-white px-4 py-2 rounded hover:bg-red-700 dark:hover:bg-red-600 transition flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                </svg>
                                ดูตัวอย่าง (Trailer)
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="p-6 border-t dark:border-gray-700">
                <h2 class="font-bold text-xl mb-3 text-gray-800 dark:text-white">เรื่องย่อ</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ $anime->description ?: 'ไม่มีข้อมูลเรื่องย่อ' }}
                </p>
                
                @if($anime->characters && is_array($anime->characters))
                    <h2 class="font-bold text-xl mb-3 text-gray-800 dark:text-white">ตัวละครหลัก</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach(array_slice($anime->characters, 0, 8) as $character)
                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded text-center">
                                <div class="bg-gray-200 dark:bg-gray-600 border-2 border-dashed rounded-full w-16 h-16 mx-auto mb-2" />
                                <p class="font-medium text-gray-800 dark:text-white">{{ $character }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">นักพากย์</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">รีวิวและเรตติ้ง</h2>
        
        <!-- Rating Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center mb-4">
                <div class="text-4xl font-bold text-indigo-700 dark:text-indigo-400 mr-6">
                    {{ number_format($anime->average_rating, 1) }}
                </div>
                <div>
                    <div class="flex items-center mb-2">
                        <span class="text-yellow-500 mr-2">★</span>
                        <span class="font-bold text-gray-800 dark:text-white">{{ $anime->reviews->count() }} รีวิว</span>
                    </div>
                </div>
            </div>
            
            <!-- Rating Distribution -->
            <div class="mb-4">
                <div class="flex items-center mb-1">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">5 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-500 rounded-full" style="width: {{ $anime->reviews->where('rating', 5)->count() > 0 ? ($anime->reviews->where('rating', 5)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 5)->count() }}</span>
                </div>
                <div class="flex items-center mb-1">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">4 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-400 rounded-full" style="width: {{ $anime->reviews->where('rating', 4)->count() > 0 ? ($anime->reviews->where('rating', 4)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 4)->count() }}</span>
                </div>
                <div class="flex items-center mb-1">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">3 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-300 rounded-full" style="width: {{ $anime->reviews->where('rating', 3)->count() > 0 ? ($anime->reviews->where('rating', 3)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 3)->count() }}</span>
                </div>
                <div class="flex items-center mb-1">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">2 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-200 rounded-full" style="width: {{ $anime->reviews->where('rating', 2)->count() > 0 ? ($anime->reviews->where('rating', 2)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 2)->count() }}</span>
                </div>
                <div class="flex items-center">
                    <span class="w-10 text-sm text-gray-700 dark:text-gray-300">1 ดาว</span>
                    <div class="w-32 h-2 bg-gray-200 dark:bg-gray-700 rounded-full mx-2">
                        <div class="h-2 bg-yellow-100 rounded-full" style="width: {{ $anime->reviews->where('rating', 1)->count() > 0 ? ($anime->reviews->where('rating', 1)->count() / $anime->reviews->count()) * 100 : 0 }}%"></div>
                    </div>
                    <span class="w-8 text-sm text-right text-gray-600 dark:text-gray-400">{{ $anime->reviews->where('rating', 1)->count() }}</span>
                </div>
            </div>
        </div>
        
        <!-- Add Review Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">เขียนรีวิวของคุณ</h3>
            <form method="POST" action="#">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">ให้คะแนน</label>
                    <div class="flex space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="rating-btn w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center hover:bg-yellow-400" data-rating="{{ $i }}">
                                <span class="text-gray-700 dark:text-gray-300">★</span>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="0">
                </div>
                <div class="mb-4">
                    <label for="review-text" class="block text-gray-700 dark:text-gray-300 mb-2">รีวิว</label>
                    <textarea id="review-text" name="review" rows="4" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white" placeholder="เขียนรีวิวของคุณที่นี่..."></textarea>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                    ส่งรีวิว
                </button>
            </form>
        </div>
        
        <!-- Reviews List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">รีวิวจากผู้ใช้งาน</h3>
            
            @if($anime->reviews->count() > 0)
                @foreach($anime->reviews as $review)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4 last:border-0 last:mb-0 last:pb-0">
                    <div class="flex items-center mb-2">
                        <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed rounded-full w-10 h-10 mr-3" />
                        <div>
                            <div class="font-bold text-gray-800 dark:text-white">{{ $review->user->name ?? 'ผู้ใช้ไม่ระบุ' }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $review->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="ml-auto flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <span class="text-yellow-500">★</span>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">★</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                    @if($review->review)
                    <p class="text-gray-700 dark:text-gray-300 mb-3">{{ $review->review }}</p>
                    @endif
                    <div class="flex space-x-4 text-sm text-gray-600 dark:text-gray-400">
                        <button class="flex items-center hover:text-green-600 dark:hover:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                            {{ $review->likes }}
                        </button>
                        <button class="flex items-center hover:text-red-600 dark:hover:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 5v10m7-10v10m0 0h2a2 2 0 002-2v-6a2 2 0 00-2-2h-2.5" />
                            </svg>
                            {{ $review->dislikes }}
                        </button>
                        <button class="hover:text-indigo-600 dark:hover:text-indigo-400">ตอบกลับ</button>
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-gray-600 dark:text-gray-400">ยังไม่มีรีวิวสำหรับอนิเมะเรื่องนี้</p>
            @endif
        </div>
    </section>

    <!-- Similar Anime Section -->
    @if($similarAnime->count() > 0)
    <section class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">คุณอาจชอบเรื่องนี้</h2>
        
        <!-- Grid of similar anime - 4 columns -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($similarAnime as $similar)
                <div class="anime-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        @if($similar->image_url)
                            <img src="{{ $similar->image_url }}" alt="{{ $similar->title }}" class="w-full h-48 object-cover rounded-t-lg" />
                        @else
                            <div class="bg-gray-200 dark:bg-gray-700 border-2 border-dashed rounded-t-lg w-full h-48 flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400">No Image</span>
                            </div>
                        @endif
                        @if($similar->is_trending)
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">มาแรง</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-1 truncate text-gray-800 dark:text-white" title="{{ $similar->title }}">
                            <a href="{{ route('anime.show', $similar->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $similar->title }}</a>
                        </h3>
                        <div class="flex items-center mb-2">
                            <span class="text-yellow-500 mr-1">★</span>
                            <span class="text-gray-800 dark:text-white">{{ $similar->rating }}/10</span>
                            @if($similar->release_date)
                                <span class="mx-2 text-gray-600 dark:text-gray-400">•</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $similar->release_date->format('Y') }}</span>
                            @endif
                        </div>
                        @if($similar->genres && is_array($similar->genres))
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach(array_slice($similar->genres, 0, 2) as $genre)
                                    <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 text-xs px-2 py-1 rounded">{{ $genre }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingButtons = document.querySelectorAll('.rating-btn');
        const ratingInput = document.getElementById('rating-input');
        
        ratingButtons.forEach(button => {
            button.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = rating;
                
                // Update the appearance of all buttons
                ratingButtons.forEach((btn, index) => {
                    if (index < rating) {
                        btn.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'hover:bg-yellow-400');
                        btn.classList.add('text-yellow-500');
                    } else {
                        btn.classList.remove('text-yellow-500');
                        btn.classList.add('bg-gray-200', 'dark:bg-gray-700', 'hover:bg-yellow-400');
                    }
                });
            });
        });
    });
</script>
@endsection