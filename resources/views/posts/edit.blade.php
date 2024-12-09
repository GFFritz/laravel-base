<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Post') }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-5">
        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.update', $post) }}" method="POST" id="postForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700">Título</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                    class="border rounded w-full px-3 py-2" placeholder="Digite o título">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Autor</label>
                <input type="text" name="author" value="{{ old('author', $post->author) }}" required
                    class="border rounded w-full px-3 py-2" placeholder="Nome do Autor">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Conteúdo</label>
                <textarea name="content" class="ckeditor border rounded w-full px-3 py-2" required>{{ old('content', $post->content) }}</textarea>
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/41.4.2/ckeditor.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/41.4.2/translations/pt-br.min.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('.ckeditor'), {
                    ckfinder: {
                        uploadUrl: '/dashboard/upload-image',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        // The UI will be English.
                        ui: 'pt-br',

                        // But the content will be edited in Arabic.
                        content: 'pt-br'
                    },
                    menuBar: {
                        isVisible: true
                    },
                    toolbar: {
                        items: [
                            'undo', 'redo',
                            '|',
                            'heading',
                            '|',
                            'fontfamily', 'fontsize', 'fontColor', 'fontBackgroundColor',
                            '|',
                            'bold', 'italic', 'strikethrough', 'subscript', 'superscript', 'code',
                            '|',
                            'link', 'uploadImage', 'blockQuote', 'codeBlock',
                            '|',
                            'alignment',
                            '|',
                            'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent'
                        ],
                        shouldNotGroupWhenFull: true
                    }
                })
                .then(editor => {
                    const form = document.getElementById('postForm');
                    form.addEventListener('submit', () => {
                        editor.model.change(writer => {
                            const data = editor.getData();
                            writer.set(editor.model.document.getRoot(), data);
                        });
                    });

                    // Configuração para lidar com o upload de imagem
                    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                        return {
                            upload: () => {
                                return new Promise((resolve, reject) => {
                                    const data = new FormData();
                                    loader.file.then(file => {
                                        data.append('upload', file);
                                        fetch('{{ route('upload.image') }}', {
                                                method: 'POST',
                                                body: data,
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Incluindo o token CSRF
                                                }
                                            })
                                            .then(response => {
                                                if (response.ok) {
                                                    return response
                                                        .json(); // Processando resposta
                                                }
                                                throw new Error('Falha na requisição: ' +
                                                    response.statusText);
                                            })
                                            .then(result => {
                                                if (result.url) {
                                                    resolve({
                                                        default: result
                                                            .url // O formato que o CKEditor espera
                                                    });
                                                } else {
                                                    reject('URL de imagem não retornada');
                                                }
                                            })
                                            .catch(err => {
                                                reject(err.message);
                                            });
                                    });
                                });
                            }
                        };
                    };
                })
                .catch(error => {
                    console.error('Erro durante a inicialização do CKEditor:', error);
                });
        </script>
    </div>
</x-app-layout>
