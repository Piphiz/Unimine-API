<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use App\Services\HashValidator;
use App\Services\UniqueUrlHashGenerator;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    protected $url;
    protected $uniqueUrlHashGenerator;

    public function __construct(Url $url, UniqueUrlHashGenerator $uniqueUrlHashGenerator)
    {
        $this->url = $url;
        $this->uniqueUrlHashGenerator = $uniqueUrlHashGenerator;
    }

    public function index()
    {
        $url = $this->url->all();

        return response()->json(['data' => UrlResource::collection($url)], 200);
    }

    public function show($hash)
    {
        try {
            $url = $this->url->findByHashOrFail($hash);
            return response()->json(['data' => $url], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Não existe um registro associado ao hash informado'], 404);
        }
    }

    public function store(UrlRequest $request, Url $url)
    {
        try {
            $data = $request->only(['link', 'user_id']);
            $url = $this->url->findByLink($data['link']);

            if ($url) {
                return response()->json([
                    'data' => [
                        'id' => $url->id,
                        'link' => $url->link,
                        'hash' => $url->hash,
                        'created_at' => $url->created_at->format('d-m-Y H:i:s')
                    ]
                ]);
            }

            $hash = $this->uniqueUrlHashGenerator->generate();

            if (!HashValidator::validate($hash)) {
                return response()->json(['message' => 'Erro interno do servidor!'], 500);
            }

            $url = $this->url->create([
                'link' => $data['link'],
                'hash' => $hash
            ]);

            return response()->json([
                'data' => [
                    'id' => $url->id,
                    'link' => $url->link,
                    'hash' => $url->hash,
                    'created_at' => $url->created_at->format('d-m-Y H:i:s')
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar link'], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->url->findOrFail($id);
            $this->url->destroy($id);
            return response()->json(['message' => 'Link deletado!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar, id não encontrado!'], 404);
        }
    }
}
