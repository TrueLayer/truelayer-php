<?php

declare(strict_types=1);

namespace TrueLayer\Models\Payment\PaymentRetrieved\AuthorizationFlow;

use TrueLayer\Contracts\Payment\AuthorizationFlow\ConfigurationInterface;
use TrueLayer\Models\Model;

class Configuration extends Model implements ConfigurationInterface
{
    /**
     * @var string
     */
    protected string $providerSelectionStatus;

    /**
     * @var string
     */
    protected string $redirectStatus;

    /**
     * @var string
     */
    protected string $redirectReturnUri;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'provider_selection.status' => 'provider_selection_status',
        'redirect.status' => 'redirect_status',
        'redirect.return_uri' => 'redirect_return_uri',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'provider_selection.status' => 'required|in:supported,not_supported',
        'redirect.status' => 'required|in:supported,not_supported',
        'redirect.return_uri' => 'required_if:redirect.status,supported',
    ];

    /**
     * @return bool
     */
    public function isProviderSelectionSupported(): bool
    {
        return $this->providerSelectionStatus === 'supported';
    }

    /**
     * @return bool
     */
    public function isRedirectSupported(): bool
    {
        return $this->redirectStatus === 'supported';
    }

    /**
     * @return string|null
     */
    public function getRedirectReturnUri(): ?string
    {
        return $this->redirectReturnUri ?? null;
    }
}
