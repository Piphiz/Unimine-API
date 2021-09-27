<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    public function index()
    {
        $url = Url::all();

        return response()->json(['data' => UrlResource::collection($url)]);
    }

    public function show($id)
    {
        try {
            $url = Url::findOrFail($id);
            return response()->json(['data' => $url]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao encontrar link, id não existe'], 404);
        }
    }

    public function store(UrlRequest $request, Url $url)
    {
        try {
            $url = $request->only(['link', 'user_id']);
            $newUrl = Url::where('link', '=', $url['link'])->first();
            if ($newUrl !== null) {
                $url['hash'] = $newUrl->hash;
            } else {
                $url['hash'] = Str::random(6);
                Url::create($url);
            }
            return response()->json(['data' => $url['hash'], 'message' => 'Encurtado com sucesso'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar link']);
        }
    }

    public function destroy($id)
    {
        try {

            Url::findOrFail($id);
            Url::destroy($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar']);
        }
    }
}
