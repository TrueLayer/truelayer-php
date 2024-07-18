<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Payment\AuthorizationFlow;

use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Payment\AuthorizationFlow\Action\ActionInterface;

class Action extends Entity implements ActionInterface
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'type',
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
