<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class QuoteService
{
    private string $apiUrl = 'https://api.kanye.rest/';
    private string $defaultQuote = 'I like fish sticks'; // a Kanye SP quote default, his character said it a lot

    public function getQuotes(int $numberOfQuotes = 5)
    {
        $quotes = [];

        // debug output as per // https://github.com/guzzle/guzzle/issues/1303
        // verify default false because of local certificate issues
        $client = new Client([
            'debug' => fopen('php://stderr', 'w'),
            'defaults' => ['verify' => false]
        ]);

        $promises = [];
        for ($i = 0; $i < $numberOfQuotes; $i++) {
            $promises[] = $client->getAsync($this->apiUrl);
        }

        $responses = Promise\Utils::settle($promises)->wait();

        foreach ($responses as $response) {
            try {
                $quotes[] = json_decode($response['value']->getBody(), true)['quote'];
            } catch (\Exception $e) {
                $quotes[] = $this->defaultQuote;
            }
        }

        return $quotes;
    }
}
