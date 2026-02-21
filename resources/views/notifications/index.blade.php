<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إشعاراتي</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">الإشعارات</h2>
                        @if($notifications->where('read_at', null)->count() > 0)
                            <form action="{{ route('notifications.markAllRead') }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800">تعليم الكل كمقروء</button>
                            </form>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-3 rounded-md bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
                    @endif

                    <ul class="divide-y divide-gray-200">
                        @forelse($notifications as $notification)
                            @php $data = $notification->data; @endphp
                            <li class="py-4 {{ $notification->read_at ? '' : 'bg-gray-50' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $data['title'] ?? 'إشعار' }}</p>
                                        <p class="text-sm text-gray-600 mt-1">{{ $data['body'] ?? '' }}</p>
                                        @if(!empty($data['url']))
                                            <a href="{{ $data['url'] }}" class="text-sm text-indigo-600 hover:underline mt-1 inline-block">عرض المزيد</a>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.markRead', $notification->id) }}" method="post" class="mr-2">
                                            @csrf
                                            <button type="submit" class="text-xs text-indigo-600 hover:underline">تعليم كمقروء</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">مقروء</span>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="py-8 text-center text-gray-500">لا توجد إشعارات.</li>
                        @endforelse
                    </ul>

                    @if($notifications->hasPages())
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
