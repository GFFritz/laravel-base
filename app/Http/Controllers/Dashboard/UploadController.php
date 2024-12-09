<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image; // Importar a classe Image

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        Log::info('Dados recebidos para upload:', $request->all());

        try {
            // Validação dos dados, incluindo WebP
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Checando se o arquivo foi enviado
            if ($request->hasFile('upload')) {
                $file = $request->file('upload');

                Log::info('Arquivo enviado: ', [
                    'filename' => $file->getClientOriginalName(),
                    'mimetype' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);

                // Vamos usar Intervention Image para otimizar a imagem
                $image = Image::make($file);

                // Gerar um novo nome de arquivo
                $fileName = uniqid() . '.webp';

                // Otimização da imagem: Convertendo para WebP e salvando
                $image->encode('webp', 90); // Qualidade de 90
                $path = 'images/' . $fileName;

                // Salvar no armazenamento público
                Storage::disk('public')->put($path, (string) $image);

                // Gera a URL completa
                $url = url('storage/' . $path); // URL completa

                // Retorno da URL da imagem
                return response()->json([
                    'url' => $url
                ]);
            }

            return response()->json(['error' => 'Nenhum arquivo enviado.'], 400);
        } catch (\Exception $e) {
            Log::error('Erro durante o upload da imagem: ' . $e->getMessage(), [
                'request' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erro interno ao fazer o upload da imagem: ' . $e->getMessage()], 500);
        }
    }
}
