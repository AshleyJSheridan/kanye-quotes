<?php

namespace Tests\Feature;

use App\Providers\QuoteService;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class QuoteControllerTest extends TestCase
{
    public function testGetQuotesEndpoint()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $mockedQuotes = [
            'Quote 1',
            'Quote 2',
            'Quote 3',
            'Quote 4',
            'Quote 5',
        ];

        $quoteServiceMock = $this->createMock(QuoteService::class);
        $quoteServiceMock->method('getQuotes')->willReturn($mockedQuotes);

        $this->app->instance(QuoteService::class, $quoteServiceMock);

        $response = $this->get('/api/quotes');

        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJsonFragment(['Quote 1']);
        $response->assertJsonFragment(['Quote 2']);
        $response->assertJsonFragment(['Quote 3']);
        $response->assertJsonFragment(['Quote 4']);
        $response->assertJsonFragment(['Quote 5']);
    }

    public function testGetQuotesUnauthenticated()
    {
        $mockedQuotes = [];

        $quoteServiceMock = $this->createMock(QuoteService::class);
        $quoteServiceMock->method('getQuotes')->willReturn($mockedQuotes);

        $this->app->instance(QuoteService::class, $quoteServiceMock);

        $response = $this->get('/api/quotes');

        $response->assertStatus(401);
    }
}
