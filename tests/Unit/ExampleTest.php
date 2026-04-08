<?php

namespace Tests\Unit;

use App\Ai\Providers\ConfigurableGeminiProvider;
use App\Providers\WindowsSafeFilesystem;
use App\Support\ResilientGeminiProvider;
use Prism\Prism\Facades\Prism;
use ReflectionProperty;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function test_the_gemini_provider_uses_the_local_configurable_provider(): void
    {
        $provider = Prism::provider('gemini', [
            'api_key' => 'test-key',
            'url' => 'https://example.test',
        ]);

        $this->assertInstanceOf(ResilientGeminiProvider::class, $provider);

        $property = new ReflectionProperty(ConfigurableGeminiProvider::class, 'defaultClientOptions');

        $this->assertSame(
            config('ai.providers.gemini.verify'),
            $property->getValue($provider)['verify'] ?? null,
        );
    }

    public function test_the_windows_safe_filesystem_can_replace_an_existing_file(): void
    {
        $filesystem = new WindowsSafeFilesystem();
        $path = storage_path('framework/testing/windows-safe-filesystem-test.txt');

        $filesystem->ensureDirectoryExists(dirname($path));
        $filesystem->put($path, 'before');
        $filesystem->replace($path, 'after');

        $this->assertSame('after', $filesystem->get($path));

        $filesystem->delete($path);
    }
}
