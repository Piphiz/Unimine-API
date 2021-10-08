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

    /** @test */
    public function should_be_return_error_if_request_post_is_empty()
    {
        $data = [];
        $response = $this->json('post', '/api/url', $data);
        $response
            ->assertStatus(422);
    }

    /**
     * @test
     * @dataProvider linksInvalidsProvider
     */
    public function should_be_return_error_if_request_post_is_invalid($link)
    {
        $data = [
            'link' => $link
        ];
        $response = $this->json('post', '/api/url', $data);
        $response
            ->assertStatus(422);
    }

    /**
     * @teste
     * @dataProvider
     */
    public function should_check_if_large_link_return_database()
    {
        $link = 'https://www.google.com/search?q=nome+do+cachorro+de+tom+e+jerry&oq=nome+do+cachorro+de+&aqs=chrome.0.0i512l2j69i57j0i512l7.8088j0j7&sourceid=chrome&ie=UTF-8';
        $data = [
            'link' => $link
        ];
        $response = $this->json('post', '/api/url', $data);
        $response
            ->assertStatus(201);
        $response = $this->json('get', '/api/url/'. $response->hash);
        $this->assertEquals($link, $response->link);
    }

    /**
     * @test
     */
    public function dont_should_be_saved_duplicated_url()
    {
        $data = [
            'link' => 'https://docs.google.com/spreadsheets/d/1mJyDIvFX6O1yBSynNg_ZqlDJVdER570mluwdCt5N6pg/edit#gid=0'
        ];

        $this->json('post', '/api/url', $data)->assertStatus(201);
        $this->json('post', '/api/url', $data)->assertStatus(200);
    }

    /**
     * @test
     * @dataProvider linksProvider
     */
    public function should_return_hash_when_try_save_duplicate_url($link)
    {
        $data = [
            'link' => $link
        ];

        $responsePrimary = $this->postJson('/api/url', $data)->assertStatus(201)->decodeResponseJson();
        $dataPrimary = json_decode((string)$responsePrimary->json, true);
        $responseSecondary = $this->json('post', '/api/url', $data)->assertStatus(200)->decodeResponseJson();
        $dataSecodary = json_decode((string)$responseSecondary->json, true);
        $this->assertEquals($dataPrimary['data']['hash'], $dataSecodary['data']['hash']);

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

    public function linksInvalidsProvider() {
        return [
            ['www.br'],
            ['.com.br'],
            ['www'],
            ['.br']
        ];
    }

}

