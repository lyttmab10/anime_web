<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            เพื่อนของ {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">รายชื่อเพื่อน</h3>
                        @if($isOwnProfile)
                            <a href="{{ route('user.pending.requests') }}" class="text-indigo-600 hover:text-indigo-900">
                                คำขอเป็นเพื่อน
                                @php
                                    $pendingCount = Auth::user()->followRequests->count();
                                @endphp
                                @if($pendingCount > 0)
                                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-1">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        @endif
                    </div>

                    @if($friends->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($friends as $friendship)
                                @php
                                    $friend = $friendship->relatedUser;
                                @endphp
                                <div class="bg-gray-50 rounded-lg p-4 flex items-center">
                                    <div class="bg-gray-200 border-2 border-dashed rounded-full w-16 h-16 mr-4" />
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $friend->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ '@'.$friend->name }}</p>
                                        <div class="mt-2 flex space-x-2">
                                            <a href="{{ route('user.friends', $friend->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">ดูเพื่อน</a>
                                            <a href="{{ route('anime.show', $friend->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900">โปรไฟล์</a>
                                        </div>
                                    </div>
                                    @if($isOwnProfile)
                                        <button 
                                            class="remove-friend-btn bg-red-100 text-red-700 px-3 py-1 rounded text-sm hover:bg-red-200"
                                            data-user-id="{{ $friend->id }}"
                                        >
                                            ลบเพื่อน
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">ยังไม่มีเพื่อนในขณะนี้</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeFriendButtons = document.querySelectorAll('.remove-friend-btn');
            
            removeFriendButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    
                    if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบเพื่อนคนนี้?')) {
                        fetch(`/user/${userId}/unfriend`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                // Reload the page to update the display
                                location.reload();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            });
        });
    </script>
</x-app-layout>