<?php
declare(strict_types=1);

namespace TwilioClient;

class ConfigReader
{
    /** @var array<string, string|int> */
    private array $config;

    public function __construct(string $path)
    {
        $this->loadConfig($path);
    }

    /**
     * @return array<string, string|int>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    private function loadConfig(string $path): void
    {
        $content = file_get_contents($path);
        $this->config = json_decode($content, true);
    }
}
