<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Nova Categoria') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-5">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Nome</label>
                <input type="text" name="name" required class="border rounded w-full px-3 py-2"
                    placeholder="Digite o nome da categoria">
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</x-app-layout>
