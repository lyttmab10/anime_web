<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            คำขอเป็นเพื่อน
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">คำขอเป็นเพื่อนที่รอการยืนยัน</h3>

                    @if($requests->count() > 0)
                        <div class="space-y-4">
                            @foreach($requests as $request)
                                @php
                                    $requester = $request->user;
                                @endphp
                                <div class="bg-gray-50 rounded-lg p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-gray-200 border-2 border-dashed rounded-full w-12 h-12 mr-4" />
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $requester->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ '@'.$requester->name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button 
                                            class="accept-request-btn bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700"
                                            data-request-id="{{ $request->id }}"
                                        >
                                            ยอมรับ
                                        </button>
                                        <button 
                                            class="decline-request-btn bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700"
                                            data-request-id="{{ $request->id }}"
                                        >
                                            ปฏิเสธ
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">ไม่มีคำขอเป็นเพื่อนในขณะนี้</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Accept request buttons
            const acceptButtons = document.querySelectorAll('.accept-request-btn');
            acceptButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-request-id');
                    
                    fetch(`/friend-request/${requestId}/accept`, {
                        method: 'POST',
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
                });
            });
            
            // Decline request buttons
            const declineButtons = document.querySelectorAll('.decline-request-btn');
            declineButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const requestId = this.getAttribute('data-request-id');
                    
                    fetch(`/friend-request/${requestId}/decline`, {
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
                });
            });
        });
    </script>
</x-app-layout>