<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Illuminate\Support\Arr;

trait HasAttributes
{
    protected array $data = [];

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function fill(array $data = []): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed|null
     */
    protected function get($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    protected function set($key, $value): self
    {
        Arr::set($this->data, $key, $value);

        return $this;
    }
}
