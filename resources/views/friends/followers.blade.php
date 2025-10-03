<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ผู้ติดตามของ {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">รายชื่อผู้ติดตาม</h3>
                    </div>

                    @if($followers->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($followers as $followerRelationship)
                                @php
                                    $follower = $followerRelationship->user;
                                @endphp
                                <div class="bg-gray-50 rounded-lg p-4 flex items-center">
                                    <div class="bg-gray-200 border-2 border-dashed rounded-full w-16 h-16 mr-4" />
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $follower->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ '@'.$follower->name }}</p>
                                        <div class="mt-2 flex space-x-2">
                                            <a href="{{ route('user.friends', $follower->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">ดูเพื่อน</a>
                                            <a href="{{ route('anime.show', $follower->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">โปรไฟล์</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">ยังไม่มีผู้ติดตามในขณะนี้</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>