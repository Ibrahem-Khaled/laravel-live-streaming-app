<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-800">تواصل معنا / الدعم الفني</h2>
        <p class="text-sm text-gray-600 mt-1">أرسل رسالتك وسنرد عليك في أقرب وقت.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />
    @if(session('success'))
        <div class="mb-4 p-3 rounded-md bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="الاسم" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', auth()->user()?->name)" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="البريد الإلكتروني" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', auth()->user()?->email)" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="subject" value="الموضوع" />
            <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" required />
            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="message" value="الرسالة" />
            <textarea id="message" name="message" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
        </div>

        <x-primary-button type="submit">
            إرسال الرسالة
        </x-primary-button>
    </form>
</x-guest-layout>
