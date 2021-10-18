<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

    /**
     * @test
     */
    public function should_check_if_trying_to_register_twice_the_same_url_returns_status_200()
    {
        $data = [
            'link' => 'https://www.youtube.com/watch?v=DlkpbzXjuPM&list=RDMMDlkpbzXjuPM&start_radio=1'
        ];

        $response = $this->json('post', 'api/url', $data);
        $response
            ->assertStatus(201);
        $responseSecondary = $this->json('post', 'api/url', $data);
        $responseSecondary
            ->assertStatus(200);
    }

    /**
     * Teste deve enviar um link sem http e a API deve retornar 201
     * @test
     */
    public function should_send_a_link_without_http_and_the_API_should_return_201()
    {
        $data = [
            'link' => 'www.teteu.com.br'
        ];

        $response = $this->json('post', 'api/url', $data);
        $response
            ->assertStatus(201);
    }

    /**
     * Teste deve enviar um link com https a api nao modifica
     * @test
     */
    public function should_send_a_link_with_https_the_api_does_not_modify()
    {
        $dataPrimary = [
            'link' => 'http://www.google.com.br'
        ];

        $responsePrimary = $this->json('post', 'api/url', $dataPrimary);
        $responsePrimary
            ->assertStatus(201);

        $dataSecondary = [
            'link' => 'www.google.com.br'
        ];

        $responseSecondary = $this->json('post', 'api/url', $dataSecondary);
        $responseSecondary
            ->assertStatus(200);
    }

    /**
     * Teste deve verificar se a hash passada é válida
     * @test
     */
    public function should_verify_if_hash_is_valid() {
        $link = 'https://www.google.com/search?q=nome+do+cachorro+de+tom+e+jerry&oq=nome+do+cachorro+de+&aqs=chrome.0.0i512l2j69i57j0i512l7.8088j0j7&sourceid=chrome&ie=UTF-8';
        $data = [
            'link' => $link
        ];
        $response = $this->json('post', '/api/url', $data);
        $hash = $response->decodeResponseJson()['data']['hash'];
        $response
            ->assertStatus(201);
        $this->json('get', '/api/url/'. $hash)->assertStatus(200);

    }

    /**
     * Teste deve enviar um hash invalido e retornar 404
     * @test
     */
    public function should_verify_if_hash_is_invalid() {
        $hash = substr(Hash::make('teste'), 0, 6);
        $this->json('get', '/api/url/'. $hash)->assertStatus(404);
    }

    /**
     * Teste deve enviar um hash invalido e retornar 404
     * @test
     */
    public function should_verify_if_hash_returned_have_six_caracters_alphanumerics_lowercase() {
        $link = 'https://www.google.com/search?q=nome+do+cachorro+de+tom+e+jerry&oq=nome+do+cachorro+de+&aqs=chrome.0.0i512l2j69i57j0i512l7.8088j0j7&sourceid=chrome&ie=UTF-8';
        $data = [
            'link' => $link
        ];
        $response = $this->json('post', '/api/url', $data);
        $hash = $response->decodeResponseJson()['data']['hash'];
        $this
            ->assertTrue((bool)preg_match("/^([0-9a-z]){6}$/", $hash));
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
            ['.com.br'],
            ['www'],
            ['.br']
        ];
    }

}

