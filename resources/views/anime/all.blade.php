@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
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
</div>
@endsection