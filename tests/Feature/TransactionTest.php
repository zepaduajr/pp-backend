<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    /** @test */
    public function check_if_payer_is_not_user()
    {
        $response = $this->postJson('/api/transaction', [
            'value' => 10,
            'payer' => 15,
            'payee' => 8
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['The payer is not a user.'])
            ->assertJsonStructure(["title", "status", "details"]);
    }

    /** @test */
    public function check_if_payer_balance_is_insufficient()
    {
        $response = $this->postJson('/api/transaction', [
            'value' => 150,
            'payer' => 1,
            'payee' => 8
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['Insufficient funds.'])
            ->assertJsonStructure(["title", "status", "details"]);
    }

    /** @test */
    public function check_if_payer_and_payee_are_equal()
    {
        $response = $this->postJson('/api/transaction', [
            'value' => 150,
            'payer' => 1,
            'payee' => 1
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['The payee and payer must be different.'])
            ->assertJsonStructure(["title", "status", "details"]);
    }

    /** @test */
    public function check_if_value_is_not_positive_number()
    {
        $response = $this->postJson('/api/transaction', [
            'value' => -10,
            'payer' => 1,
            'payee' => 1
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonFragment(['The value must be at least 0.01.'])
            ->assertJsonStructure(["title", "status", "details"]);
    }

    /** @test */
    public function check_if_transaction_is_successful()
    {
        $response = $this->postJson('/api/transaction', [
            'value' => 50,
            'payer' => 2,
            'payee' => 15
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['Success'])
            ->assertJsonStructure(["title", "status", "details"]);
    }

    /** @test */
    public function check_if_transaction_is_not_authorized()
    {
        $_ENV['URL_AUTORIZACAO'] = 'http://teste';

        $response = $this->postJson('/api/transaction', [
            'value' => 50,
            'payer' => 2,
            'payee' => 15
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonFragment(['Not Authorized.'])
            ->assertJsonStructure(["title", "status", "details"]);
    }
}
