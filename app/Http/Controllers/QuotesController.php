<?php

namespace App\Http\Controllers;

use App\Providers\QuoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class QuotesController extends Controller
{
    private int $quoteCount = 5;
    private QuoteService $quoteService;
    private string $cacheKey;
    private int $cacheDurationMinutes = 30;

    public function __construct(QuoteService $quoteService, Request $request)
    {
        $this->quoteService = $quoteService;

        $token = $request->bearerToken();
        $this->cacheKey = 'kanye_quotes_' . $token;
    }

    public function getQuotes()
    {
        if (Cache::has($this->cacheKey))
        {
            $quotes = Cache::get($this->cacheKey);
        }
        else
        {
            $quotes = $this->quoteService->getQuotes($this->quoteCount);

            Cache::put($this->cacheKey, $quotes, now()->addMinutes($this->cacheDurationMinutes));
        }

        return response()->json($quotes);
    }

    public function refreshQuotes()
    {
        if (Cache::has($this->cacheKey))
        {
            Cache::forget($this->cacheKey);
        }

        return $this->getQuotes();
    }
}
