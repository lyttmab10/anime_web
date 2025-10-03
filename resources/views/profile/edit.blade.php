<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Anime Stats Section -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-3xl">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">{{ __('สถิติอนิเมะ') }}</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        <div class="bg-indigo-50 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-indigo-700">{{ $watchlistStats['watching'] }}</p>
                            <p class="text-gray-600 text-sm">กำลังดู</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-green-700">{{ $watchlistStats['completed'] }}</p>
                            <p class="text-gray-600 text-sm">ดูแล้ว</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-yellow-700">{{ $watchlistStats['planned'] }}</p>
                            <p class="text-gray-600 text-sm">อยากดู</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-purple-700">{{ $watchlistStats['on_hold'] }}</p>
                            <p class="text-gray-600 text-sm">พักดูก่อน</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-red-700">{{ $watchlistStats['dropped'] }}</p>
                            <p class="text-gray-600 text-sm">dropped</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-700 mb-2">รีวิวที่เขียน: {{ $reviewCount }} รีวิว</h4>
                    </div>
                    
                    @if($recentWatched->count() > 0)
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">ดูเร็วๆ นี้</h4>
                        <div class="space-y-2">
                            @foreach($recentWatched as $watched)
                            <div class="flex items-center p-2 border border-gray-200 rounded">
                                <div class="bg-gray-200 border-2 border-dashed rounded w-10 h-10 mr-3" />
                                <div>
                                    <p class="font-medium">{{ $watched->anime->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $watched->updated_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
