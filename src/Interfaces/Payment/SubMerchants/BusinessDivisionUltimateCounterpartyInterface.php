<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Payment\SubMerchants;

interface BusinessDivisionUltimateCounterpartyInterface extends UltimateCounterpartyInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     *
     * @return $this
     */
    public function id(string $id): self;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): self;
}
