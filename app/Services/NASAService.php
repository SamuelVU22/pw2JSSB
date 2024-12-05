<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NASAService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('NASA_API_KEY', 'GPNcKV3zG4IC74Q0e2esJjoWSLDDXgIXwVRadTcq');
    }

    public function fetchPictures($count = 10)
    {
        $response = Http::get('https://api.nasa.gov/planetary/apod', [
            'api_key' => $this->apiKey,
            'count' => $count,
        ]);

        return $response->ok() ? $response->object() : [];
    }
}
