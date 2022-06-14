<?php declare(strict_types=1);

namespace Storage\Service\Adapters;


use Phalcon\Cache\Adapter\AdapterInterface;

class RuntimeCacheAdapter implements AdapterInterface
{
    private array $data;

    public function clear(): bool
    {
        $this->data = [];

        return true;
    }

    public function decrement(string $key, int $value = 1)
    {
    }

    public function delete(string $key): bool
    {
        unset($this->data[$key]);

        return true;
    }

    public function get(string $key, $defaultValue = null)
    {
        return $this->data[$key] ?? $defaultValue;
    }

    public function getAdapter()
    {
        return $this;
    }

    public function getKeys(string $prefix = ''): array
    {
        return [];
    }

    public function getPrefix(): string
    {
        return '';
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function increment(string $key, int $value = 1)
    {
    }

    public function set(string $key, $value, $ttl = null): bool
    {
        $this->data[$key] = $value;

        return true;
    }
}
