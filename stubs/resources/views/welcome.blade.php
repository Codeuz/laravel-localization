<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('welcome.home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto py-1 sm:py-4 lg:py-6 px-6 lg:px-8">
            {{ __('welcome.welcome') }}
        </div>
    </div>
</x-app-layout>
