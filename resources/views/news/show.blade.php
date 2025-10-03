@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <article class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($news->image_url)
            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-64 md:h-96 object-cover">
        @else
            <div class="bg-gray-200 border-2 border-dashed w-full h-64 md:h-96 flex items-center justify-center">
                <span class="text-gray-500">ไม่มีรูปภาพ</span>
            </div>
        @endif
        
        <div class="p-8">
            <div class="flex flex-wrap items-center justify-between mb-4">
                <div>
                    <span class="bg-indigo-600 text-white text-sm px-3 py-1 rounded mr-4">
                        @switch($news->type)
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
                    <span class="text-gray-600">{{ $news->formatted_published_at }}</span>
                </div>
                <span class="text-gray-600">{{ $news->views }} ผู้อ่าน</span>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $news->title }}</h1>
            
            <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                <div class="bg-gray-200 border-2 border-dashed rounded-full w-10 h-10 mr-3" />
                <div>
                    <p class="font-medium text-gray-900">{{ $news->author_name }}</p>
                    <p class="text-sm text-gray-600">ผู้เขียน</p>
                </div>
            </div>
            
            @if($news->tags && is_array($news->tags) && count($news->tags) > 0)
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach($news->tags as $tag)
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
            
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($news->content)) !!}
            </div>
        </div>
    </article>
    
    <!-- Related Articles -->
    @if($relatedNews && $relatedNews->count() > 0)
    <section class="mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">บทความที่คุณอาจชอบ</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedNews as $related)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative">
                    @if($related->image_url)
                        <img src="{{ $related->image_url }}" alt="{{ $related->title }}" class="w-full h-32 object-cover">
                    @else
                        <div class="bg-gray-200 border-2 border-dashed w-full h-32 flex items-center justify-center">
                            <span class="text-gray-500 text-sm">ไม่มีรูปภาพ</span>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2 line-clamp-2">
                        <a href="{{ route('news.show', $related->slug) }}" class="text-gray-800 hover:text-indigo-600">
                            {{ $related->title }}
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm">{{ $related->formatted_published_at }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection