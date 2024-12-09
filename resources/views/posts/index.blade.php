<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-5">
        <div class="flex justify-between">
            <a href="{{ route('posts.create') }}" class="bg-blue-500 text-white px-4 py-2">Criar Novo Post</a>
            <!-- Botão para Limpar Filtros -->
            <a href="{{ route('posts.index') }}" class="btn btn-secondary ml-2">Limpar Filtros</a>
        </div>
        <div class="flex justify-between my-4">

            <div class="relative w-64">
                <input type="text" id="title-search" placeholder="Filtrar pelo título"
                    class="border rounded px-2 py-1 w-full" autocomplete="off">

                <!-- Botão de Limpar Busca -->
                <button id="clear-search" class="absolute right-0 top-0 mt-1 mr-1 text-gray-500">
                    X
                </button>

                <!-- Sugestões de títulos -->
                <ul id="suggestions" class="bg-white border mt-2 rounded hidden absolute z-10 w-full"></ul>
            </div>

            <div class="flex items-center">
                <label class="mr-2">Ordenar por:</label>
                <select id="sort-select" class="border rounded px-2 py-1 w-20">
                    <option value="created_at" {{ $sortBy == 'created_at' ? 'selected' : '' }}>Data</option>
                    <option value="title" {{ $sortBy == 'title' ? 'selected' : '' }}>Título</option>
                </select>

                <select id="order-select" class="border rounded px-2 py-1 ml-2 w-36">
                    <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>DESC</option>
                    <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>ASC</option>
                </select>

                <button id="sort-button" class="btn btn-secondary ml-2">Aplicar</button>
            </div>

        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Autor
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de
                        publicação
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destaque
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($posts as $post)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('posts.show', $post) }}" class="text-blue-600">{{ $post->title }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $post->author }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($post->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('posts.toggleFeatured', $post) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600">
                                    {{ $post->is_featured ? 'Remover Destaque' : 'Definir Destaque' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('posts.edit', $post) }}" class="text-blue-600">Editar</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $posts->links() }}
    </div>
</x-app-layout>

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Array com os títulos dos posts
            const titles = @json($titles); // Passar o array de títulos para o JavaScript

            $('#title-search').on('keyup', function() {
                const searchValue = $(this).val().toLowerCase();
                $('#suggestions').empty(); // Limpa as sugestões anteriores

                // Aqui não haverá mais condição para o comprimento do texto
                const filteredTitles = titles.filter(title => title.toLowerCase().includes(searchValue));

                if (filteredTitles.length > 0) {
                    $('#suggestions').removeClass('hidden'); // Mostra a lista de sugestões
                    filteredTitles.forEach(title => {
                        $('#suggestions').append(
                            `<li class="p-2 hover:bg-gray-200 cursor-pointer">${title}</li>`
                        );
                    });
                } else {
                    $('#suggestions').addClass(
                        'hidden'); // Esconde a lista de sugestões quando não há correspondência
                }
            });

            // Se uma sugestão for clicada
            $(document).on('click', '#suggestions li', function() {
                const selectedTitle = $(this).text();
                $('#title-search').val(selectedTitle);
                $('#suggestions').addClass('hidden'); // Esconde a lista de sugestões após selecionar

                // Redirecionar para a busca pela pesquisa
                const filterUrl = "{{ route('posts.index') }}?filter=" + encodeURIComponent(selectedTitle);
                window.location.href = filterUrl;
            });

            // Lógica para ordenar os posts
            $('#sort-button').on('click', function() {
                const sortBy = $('#sort-select').val();
                const sortOrder = $('#order-select').val();
                const filterUrl = "{{ route('posts.index') }}?sort_by=" + sortBy + "&sort_order=" +
                    sortOrder;

                window.location.href = filterUrl; // Redireciona para a listagem com os filtros aplicados
            });
        });
    </script>
