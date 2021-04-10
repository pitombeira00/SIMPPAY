<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    /**
     * Testando tentativa de acessar a pagina sem o token api.
     */
    public function testCreateTransaction_failToken()
    {
        $response = $this->post('/api/transaction');

        $response->assertStatus(302);
    }



}
