<?php

declare(strict_types=1);

namespace TrueLayer\Tests\Integration\Mocks;

use TrueLayer\Interfaces\Webhook\EventInterface;

class WebhookHandler
{
    public EventInterface $event;

    public function __invoke(EventInterface $event)
    {
        $this->event = $event;
    }
}

