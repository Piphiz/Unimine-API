<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Services\HashValidator;
use App\Services\UniqueUrlHashGenerator;
use Tests\TestCase;

class UniqueUrlHashGeneratorTest extends TestCase
{
    /**
     * Verifica se é possível gerar um Hash Único e válido
     *
     * @test
     */
    public function should_verify_if_is_unique_url()
    {
        $uniqueUrlHashGenerator = new UniqueUrlHashGenerator(new Url());
        $hash = $uniqueUrlHashGenerator->generate();
        $isValid = HashValidator::validate($hash);

        $this->assertTrue($isValid);
    }
}
