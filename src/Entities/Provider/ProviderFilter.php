<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider;

use TrueLayer\Constants\Countries;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\ReleaseChannels;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
use TrueLayer\Entities\Entity;
use TrueLayer\Validation\AllowedConstant;

class ProviderFilter extends Entity implements ProviderFilterInterface
{
    /**
     * @var string[]
     */
    protected array $countries;

    /**
     * @var string
     */
    protected string $releaseChannel;

    /**
     * @var string[]
     */
    protected array $customerSegments;

    /**
     * @var string[]
     */
    protected array $providerIds;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'countries',
        'release_channel',
        'customer_segments',
        'provider_ids',
    ];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return [
            'countries.*' => ['string', AllowedConstant::in(Countries::class)],
            'release_channel' => ['string', AllowedConstant::in(ReleaseChannels::class)],
            'customer_segments.*' => ['string', AllowedConstant::in(CustomerSegments::class)],
            'provider_ids.*' => ['string'],
        ];
    }

    public function countries(array $countries): ProviderFilterInterface
    {
        $this->countries = $countries;

        return $this;
    }

    public function releaseChannel(string $releaseChannel): ProviderFilterInterface
    {
        $this->releaseChannel = $releaseChannel;

        return $this;
    }

    public function customerSegments(array $customerSegments): ProviderFilterInterface
    {
        $this->customerSegments = $customerSegments;

        return $this;
    }

    public function providerIds(array $providerIds): ProviderFilterInterface
    {
        $this->providerIds = $providerIds;

        return $this;
    }
}
