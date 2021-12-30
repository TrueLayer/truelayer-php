<?php

declare(strict_types=1);

namespace TrueLayer\Services\Util;

use \Closure;

class Retry
{
    /**
     * @var Closure|null
     */
    public static ?Closure $testSleeper = null;

    /**
     * @var Closure |null
     */
    private ?Closure $when = null;

    /**
     * @var int
     */
    private int $maxRetries = 4;

    /**
     * @param Closure $when
     * @return $this
     */
    public function when(Closure $when): self
    {
        $this->when = $when;
        return $this;
    }

    /**
     * @param int $maxRetries
     * @return $this
     */
    public function maxRetries(int $maxRetries): self
    {
        $this->maxRetries = $maxRetries;
        return $this;
    }

    /**
     * @param Closure $closure
     * @return mixed
     * @throws \Exception
     */
    public function start(Closure $closure)
    {
       return $this->attempt($closure, 0);
    }

    /**
     * @param Closure $closure
     * @param int $attempt
     * @return mixed|void
     * @throws \Exception
     */
    private function attempt(Closure $closure, int $attempt)
    {
        try {
            return $closure($attempt);
        } catch (\Exception $e) {
            if ($this->allowRetry($e, $attempt)) {
                $this->delay($attempt);
                return $this->attempt($closure, $attempt + 1);
            }

            throw $e;
        }
    }

    /**
     * @param \Exception $e
     * @param int $attempt
     * @return bool
     */
    private function allowRetry(\Exception $e, int $attempt): bool
    {
        if ($attempt >= $this->maxRetries) {
            return false;
        }

        if ($this->when === null) {
            return true;
        }

        return (bool) ($this->when)($e);
    }

    /**
     * @param int $attempt
     */
    private function delay(int $attempt): void
    {
        $delay = \mt_rand(0, 1000000) + (\pow(2, $attempt) * 1000000);
        self::$testSleeper ? (self::$testSleeper)($delay) : usleep($delay);
    }

    /**
     * @param int $max
     * @return static
     */
    public static function max(int $max): self
    {
        return (new static())->maxRetries($max);
    }
}
