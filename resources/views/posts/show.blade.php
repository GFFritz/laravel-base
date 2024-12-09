<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visualizar Post') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-5">
        <div class="mb-4">
            <h3 class="text-lg font-semibold">{{ $post->title }}</h3>
            <p class="text-gray-600">Autor: {{ $post->author }}</p>
            <p class="text-gray-600">Destaque: {{ $post->is_featured ? 'Sim' : 'Não' }}</p>
            <div class="mt-4">{!! $post->content !!}</div>
        </div>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</x-app-layout>
