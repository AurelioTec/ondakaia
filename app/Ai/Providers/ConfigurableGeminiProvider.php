<?php

namespace App\Ai\Providers;

use Illuminate\Http\Client\PendingRequest;
use Prism\Prism\Providers\Gemini\Gemini;

class ConfigurableGeminiProvider extends Gemini
{
    /**
     * @param  array<string, mixed>  $defaultClientOptions
     */
    public function __construct(
        #[\SensitiveParameter] string $apiKey,
        string $url,
        protected array $defaultClientOptions = [],
    ) {
        parent::__construct($apiKey, $url);
    }

    /**
     * @param  array<string, mixed>  $options
     * @param  array<mixed>  $retry
     */
    protected function client(array $options = [], array $retry = [], ?string $baseUrl = null): PendingRequest
    {
        return parent::client(
            options: array_replace($this->defaultClientOptions, $options),
            retry: $retry,
            baseUrl: $baseUrl,
        );
    }
}
