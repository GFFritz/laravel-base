<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Post') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-5">
        <!-- Criar e Editar Post -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('posts.update', $post) }}" method="POST" id="postForm">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700">Título</label>
                <input type="text" name="title" value="{{ $post->title }}" required
                    class="border rounded w-full px-3 py-2" placeholder="Digite o título">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Autor</label>
                <input type="text" name="author" value="{{ $post->author }}" required
                    class="border rounded w-full px-3 py-2" placeholder="Nome do Autor">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Conteúdo</label>
                <textarea name="content" class="ckeditor border rounded w-full px-3 py-2" required>{{ $post->content }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Categorias</label>
                <select name="categories[]" multiple class="border rounded w-full px-3 py-2">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $post->categories->contains($category->id) ? 'selected' : '' }}>{{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_featured" {{ $post->is_featured ? 'checked' : '' }}
                        class="form-checkbox">
                    <span class="ml-2">Destaque</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>

        <script>
            ClassicEditor
                .create(document.querySelector('.ckeditor'))
                .then(editor => {
                    const form = document.getElementById('postForm');
                    form.addEventListener('submit', () => {
                        editor.model.change(writer => {
                            const data = editor.getData();
                            writer.set(editor.model.document.getRoot(), data);
                        });
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('.ckeditor'))
            .catch(error => {
                console.error(error);
            });
    </script>
</x-app-layout>
