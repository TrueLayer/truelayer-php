<?php

declare(strict_types=1);

namespace TrueLayer\Interfaces\Remitter\RemitterVerification;

use TrueLayer\Interfaces\Provider\PreselectedProviderSelectionInterface;
use TrueLayer\Interfaces\Provider\UserSelectedProviderSelectionInterface;

interface RemitterVerificationBuilderInterface
{
    /**
     * @return AutomatedRemitterVerificationInterface
     */
    public function automated(): AutomatedRemitterVerificationInterface;

}
