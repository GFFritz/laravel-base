{{-- resources/views/users/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2">Adicionar Usuário</a>
            </div>

            <!-- Filtro de Status de Usuários -->
            <div class="mb-4">
                <form method="GET" action="{{ route('users.index') }}" class="flex space-x-2">
                    <select name="status" onchange="this.form.submit();" class="border rounded-md p-2">
                        <option value="">Todos</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                    @csrf
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nome</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->role->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->status ? 'Ativo' : 'Inativo' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('users.toggleStatus', $user) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                            {{ $user->status ? 'Desativar' : 'Ativar' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="text-indigo-600 hover:text-indigo-900 ml-4">Editar</a>
                                    <form action="{{ route('users.sendResetPassword', $user) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 ml-4">Enviar
                                            Reset</button>
                                    </form>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 ml-4">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">Nenhum usuário
                                    encontrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
