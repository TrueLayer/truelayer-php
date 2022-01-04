<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Illuminate\Support\Arr;
use TrueLayer\Exceptions\InvalidArgumentException;

trait HasAttributes
{
    /**
     * @var mixed[]
     */
    protected array $data = [];

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param mixed[] $data
     *
     * @return $this
     */
    public function fill(array $data = []): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string|int|null $key
     * @param null|mixed $default
     *
     * @return mixed|null
     */
    protected function get($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @param string|null $key
     * @param mixed $value
     *
     * @return $this
     */
    protected function set($key, $value): self
    {
        Arr::set($this->data, $key, $value);

        return $this;
    }
}
