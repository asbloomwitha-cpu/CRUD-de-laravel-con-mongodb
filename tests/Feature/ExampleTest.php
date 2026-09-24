<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Verifica que la ruta raíz redirige correctamente al catálogo de productos.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/productos');

        $catalogoResponse = $this->get('/productos');
        $catalogoResponse->assertStatus(200);
    }
}
