<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();

        // Filtros
        if ($request->filled('filter')) {
            $query->where('title', 'like', '%' . $request->filter . '%');
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at'); // Campo por qual você quer ordenar
        $sortOrder = $request->get('sort_order', 'desc'); // Ordem padrão

        // Verificar se a ordenação é válida
        if (!in_array($sortBy, ['title', 'created_at']) || !in_array($sortOrder, ['asc', 'desc'])) {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }

        $posts = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        // Obter todos os títulos dos posts
        $titles = Post::pluck('title');

        return view('posts.index', compact('posts', 'titles', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validação
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'is_featured' => 'boolean', // Tornar este campo opcional
            'categories' => 'array',
        ]);

        // Convertendo "on" para verdadeiro e lidando com a ausência do campo
        $validatedData['is_featured'] = $request->has('is_featured') ? true : false;

        try {
            // Criar o post
            $post = Post::create($validatedData);
            $post->categories()->sync($request->categories);

            return redirect()->route('posts.index')->with('success', 'Post criado com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro ao criar post: ' . $e->getMessage(), [
                'request' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Erro ao criar post. Tente novamente.')->withInput();
        }
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        // Validação
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'is_featured' => 'boolean',
            'categories' => 'array',
        ]);

        // Determinar se o post é destacado
        $validatedData['is_featured'] = $request->has('is_featured') ? true : false;

        try {
            // Atualizar o post
            $post->update($validatedData);
            $post->categories()->sync($request->categories);

            return redirect()->route('posts.index')->with('success', 'Post atualizado com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar post: ' . $e->getMessage(), [
                'post_id' => $post->id,
                'request' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Erro ao atualizar post. Tente novamente.')->withInput();
        }
    }

    public function toggleFeatured(Post $post)
    {
        // Alterna o valor de is_featured
        $post->is_featured = !$post->is_featured;
        $post->save();

        // Adiciona um log (opcional)
        Log::info('Post destaque atualizado', [
            'post_id' => $post->id,
            'new_featured_status' => $post->is_featured,
        ]);

        return redirect()->route('posts.index')->with('success', 'Destaque do post atualizado com sucesso');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post excluído com sucesso');
    }
}
