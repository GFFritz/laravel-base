<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    // Função para listar as categorias
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Função para mostrar o formulário de criação
    public function create()
    {
        return view('categories.create');
    }

    // Função para armazenar uma nova categoria
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        try {
            Category::create($request->only('name'));
            return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar categoria: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao criar categoria. Tente novamente.')->withInput();
        }
    }

    // Função para mostrar o formulário de edição
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Função para atualizar uma categoria existente
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        try {
            $category->update($request->only('name'));
            return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar categoria: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao atualizar categoria. Tente novamente.')->withInput();
        }
    }

    // Função para deletar uma categoria
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir categoria: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao excluir categoria. Tente novamente.');
        }
    }
}
