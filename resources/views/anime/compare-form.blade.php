@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden p-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8 text-center">เปรียบเทียบอนิเมะ</h1>
        
        <form method="POST" action="{{ route('anime.compare') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="anime1_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">อนิเมะแรก <span class="text-red-500">*</span></label>
                    <select name="anime1_id" id="anime1_id" required class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        <option value="">เลือกอนิเมะ</option>
                        @foreach($animes as $anime)
                            <option value="{{ $anime->id }}">{{ $anime->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="anime2_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">อนิเมะที่สอง <span class="text-red-500">*</span></label>
                    <select name="anime2_id" id="anime2_id" required class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        <option value="">เลือกอนิเมะ</option>
                        @foreach($animes as $anime)
                            <option value="{{ $anime->id }}">{{ $anime->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="anime3_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">อนิเมะที่สาม (ไม่บังคับ)</label>
                    <select name="anime3_id" id="anime3_id" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        <option value="">เลือกอนิเมะ (ไม่บังคับ)</option>
                        @foreach($animes as $anime)
                            <option value="{{ $anime->id }}">{{ $anime->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="anime4_id" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">อนิเมะที่สี่ (ไม่บังคับ)</label>
                    <select name="anime4_id" id="anime4_id" class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-900 dark:text-white">
                        <option value="">เลือกอนิเมะ (ไม่บังคับ)</option>
                        @foreach($animes as $anime)
                            <option value="{{ $anime->id }}">{{ $anime->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="text-center pt-6">
                <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg shadow hover:bg-indigo-700 transition">
                    เปรียบเทียบอนิเมะ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection