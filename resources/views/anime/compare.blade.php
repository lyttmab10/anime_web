@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">เปรียบเทียบอนิเมะ</h1>
            <a href="{{ route('anime.compare.form') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                เปรียบเทียบอีกครั้ง
            </a>
        </div>
        
        <!-- Comparison Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-md">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">คุณสมบัติ</th>
                        @foreach($animes as $anime)
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            <div class="flex flex-col items-center">
                                @if($anime->image_url)
                                    <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-16 h-16 object-cover rounded mb-2">
                                @else
                                    <div class="bg-gray-200 dark:bg-gray-600 border-2 border-dashed rounded w-16 h-16 flex items-center justify-center mb-2">
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">No Image</span>
                                    </div>
                                @endif
                                <span class="text-gray-800 dark:text-white font-medium">{{ \Str::limit($anime->title, 15) }}</span>
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">ชื่อเรื่อง</td>
                        @foreach($animes as $anime)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">{{ $anime->title }}</td>
                        @endforeach
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">เรตติ้ง</td>
                        @foreach($animes as $anime)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                            <div class="flex items-center justify-center">
                                <span class="text-yellow-500 mr-1">★</span>
                                <span>{{ $anime->rating }}/10</span>
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">ปีที่ฉาย</td>
                        @foreach($animes as $anime)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                            {{ $anime->release_date ? $anime->release_date->format('Y') : 'ไม่ระบุ' }}
                        </td>
                        @endforeach
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">สตูดิโอ</td>
                        @foreach($animes as $anime)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                            {{ $anime->studio ?: 'ไม่ระบุ' }}
                        </td>
                        @endforeach
                    </tr>
                    
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">จำนวนตอน</td>
                        @foreach($animes as $anime)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                            {{ $anime->episodes ?: 'ไม่ระบุ' }}
                        </td>
                        @endforeach
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">สถานะ</td>
                        @foreach($animes as $anime)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                            @if($anime->status == 'not_yet_aired')
                                ยังไม่เริ่มฉาย
                            @elseif($anime->status == 'currently_airing')
                                กำลังออกอากาศ
                            @else
                                ฉายจบแล้ว
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">แนว</td>
                        @foreach($animes as $anime)
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 text-center">
                            @if($anime->genres && is_array($anime->genres))
                                @foreach($anime->genres as $genre)
                                    <span class="inline-block bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 text-xs px-2 py-1 rounded-full mr-1 mb-1">{{ $genre }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">เรื่องย่อ</td>
                        @foreach($animes as $anime)
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 text-center">
                            {{ \Str::limit($anime->description, 100) ?: '-' }}
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap gap-4 justify-center">
            @foreach($animes as $anime)
            <a href="{{ route('anime.show', $anime->id) }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow hover:bg-indigo-700 transition">
                ดูรายละเอียด {{ \Str::limit($anime->title, 10) }}
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection