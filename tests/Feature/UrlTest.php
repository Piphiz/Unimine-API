<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica se é possível criar uma URL curta
     *
     * @dataProvider linksProvider
     * @test
     */
    public function should_be_possible_create_a_short_url($link)
    {
        $data = [
            'link' => $link
        ];

        $response = $this->json('post', '/api/url', $data);
        $response
            ->assertStatus(201)
            ->assertSee(str_replace('/', '\/', $link))
            ->assertJsonStructure([
                'data' => ['id', 'link', 'hash', 'created_at']
            ]);
    }

    public function linksProvider(): array
    {
        return [
            ['http://www.google.com.br'],
            ['http://www.yahoo.com.br'],
            ['http://www.facebook.com.br'],
            ['https://www.teste.com.br/noticias/?cat=teste']
        ];
    }
}
