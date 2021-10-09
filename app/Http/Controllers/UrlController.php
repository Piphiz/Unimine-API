<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use App\Models\UrlActivity;
use App\Services\HashValidator;
use App\Services\UniqueUrlHashGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UrlController extends Controller
{
    protected $url;
    protected $uniqueUrlHashGenerator;
    protected $urlActivity;

    public function __construct(Url $url, UniqueUrlHashGenerator $uniqueUrlHashGenerator, UrlActivity $urlActivity)
    {
        $this->url = $url;
        $this->uniqueUrlHashGenerator = $uniqueUrlHashGenerator;
        $this->urlActivity = $urlActivity;
    }

    public function index()
    {
        $url = $this->url->all();

        return response()->json(['data' => UrlResource::collection($url)], 200);
    }

    public function show(Request $request, $hash)
    {
        try {
            $url = $this->url->findByHashOrFail($hash);
            $this->urlActivity->create([
                'url_id' => $url['id'],
                'ip' => $request->ip(),
                'type' => 'Accessed'
            ]);
            return response()->json(['data' => $url], 200);
        } catch (\Exception $e) {
            return response()->json([],404);
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
                return response()->json([],500);
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
            return response()->json( [],400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->url->findOrFail($id);
            $this->url->destroy($id);
            return response()->json([],200);
        } catch (\Exception $e) {
            return response()->json([],404);
        }
    }
}
