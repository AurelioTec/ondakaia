<?php

namespace App\Support;

use App\Ai\Providers\ConfigurableGeminiProvider;
use Illuminate\Http\Client\PendingRequest;

class ResilientGeminiProvider extends ConfigurableGeminiProvider
{
    /**
     * @param  array<string, mixed>  $defaultClientOptions
     * @param  array<mixed>  $defaultRetry
     */
    public function __construct(
        #[\SensitiveParameter] string $apiKey,
        string $url,
        array $defaultClientOptions = [],
        protected array $defaultRetry = [],
    ) {
        parent::__construct(
            apiKey: $apiKey,
            url: $url,
            defaultClientOptions: $defaultClientOptions,
        );
    }

    /**
     * @param  array<string, mixed>  $options
     * @param  array<mixed>  $retry
     */
    protected function client(array $options = [], array $retry = [], ?string $baseUrl = null): PendingRequest
    {
        $effectiveRetry = $retry !== [] ? $retry : $this->defaultRetry;

        return parent::client(
            options: $options,
            retry: $effectiveRetry,
            baseUrl: $baseUrl,
        );
    }
}
