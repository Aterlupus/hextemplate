<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use Webmozart\Assert\Assert;

class RequestJson
{
    private array $decoded = [];


    public function has(string $key): bool
    {
        return isset($this->decoded[$key]);
    }

    public function get(string $key, bool $expectExistence = true)
    {
        if ($expectExistence) {
            Assert::keyExists($this->decoded, $key);
        }
        return $this->decoded[$key] ?? null;
    }

    public function add(string $key, $value): self
    {
        Assert::keyNotExists($this->decoded, $key);
        $this->decoded[$key] = $value;
        return $this;
    }

    public function edit(string $key, $value): self
    {
        Assert::keyExists($this->decoded, $key);
        $this->decoded[$key] = $value;
        return $this;
    }

    public function set(string $key, $value): self
    {
        if ($this->has($key)) {
            $this->edit($key, $value);
        } else {
            $this->add($key, $value);
        }

        return $this;
    }

    public function append(string $key, $value): self
    {
        Assert::keyExists($this->decoded, $key);
        Assert::isArray($this->decoded[$key]);
        Assert::isArray($value);
        $this->decoded[$key] = array_merge($this->decoded[$key], $value);
        return $this;
    }

    public function apply(array $changes): self
    {
        foreach($changes as $key => $change) {
            $this->edit($key, $change);
        }
        return $this;
    }

    public function remove(string $key): self
    {
        Assert::keyExists($this->decoded, $key);
        unset($this->decoded[$key]);
        return $this;
    }

    public function toJson(): string
    {
        return json_encode($this->decoded);
    }

    public function toArray(): array
    {
        return $this->decoded;
    }

    public function getKeys(): array
    {
        return array_keys($this->decoded);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
