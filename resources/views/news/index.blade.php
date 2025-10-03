@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">ข่าวและบทความ</h1>
        <p class="text-gray-600">อัปเดตข่าวสารแวดวงอนิเมะล่าสุดและบทความวิเคราะห์</p>
    </div>
    
    <!-- Search and Filter -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <form method="GET" action="{{ route('news.index') }}" class="flex-1">
            <div class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="ค้นหาข่าวและบทความ..." 
                    value="{{ request('search') }}"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">ทุกประเภท</option>
                    <option value="news" {{ request('type') === 'news' ? 'selected' : '' }}>ข่าวสาร</option>
                    <option value="blog" {{ request('type') === 'blog' ? 'selected' : '' }}>บล็อก</option>
                    <option value="season_summary" {{ request('type') === 'season_summary' ? 'selected' : '' }}>สรุปซีซั่น</option>
                    <option value="analysis" {{ request('type') === 'analysis' ? 'selected' : '' }}>วิเคราะห์</option>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    ค้นหา
                </button>
            </div>
        </form>
    </div>
    
    <!-- News Articles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($news as $article)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="relative">
                @if($article->image_url)
                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="bg-gray-200 border-2 border-dashed w-full h-48 flex items-center justify-center">
                        <span class="text-gray-500">ไม่มีรูปภาพ</span>
                    </div>
                @endif
                <span class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded">
                    @switch($article->type)
                        @case('news')
                            ข่าวสาร
                            @break
                        @case('blog')
                            บล็อก
                            @break
                        @case('season_summary')
                            สรุปซีซั่น
                            @break
                        @case('analysis')
                            วิเคราะห์
                            @break
                        @default
                            อื่นๆ
                    @endswitch
                </span>
            </div>
            <div class="p-6">
                <h3 class="font-bold text-lg mb-2 line-clamp-2">
                    <a href="{{ route('news.show', $article->slug) }}" class="text-gray-800 hover:text-indigo-600">
                        {{ $article->title }}
                    </a>
                </h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $article->summary ?: \Str::limit(strip_tags($article->content), 100) }}</p>
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>{{ $article->formatted_published_at }}</span>
                    <span>{{ $article->views }} ผู้อ่าน</span>
                </div>
                @if($article->tags && is_array($article->tags) && count($article->tags) > 0)
                    <div class="mt-3 flex flex-wrap gap-1">
                        @foreach(array_slice($article->tags, 0, 3) as $tag)
                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">{{ $tag }}</span>
                        @endforeach
                        @if(count($article->tags) > 3)
                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">+{{ count($article->tags) - 3 }}</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-600">ไม่พบบทความที่คุณค้นหา</p>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-8">
        {{ $news->appends(request()->query())->links() }}
    </div>
</div>
@endsection