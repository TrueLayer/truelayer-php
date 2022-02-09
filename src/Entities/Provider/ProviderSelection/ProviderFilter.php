<?php

declare(strict_types=1);

namespace TrueLayer\Entities\Provider\ProviderSelection;

use TrueLayer\Constants\Countries;
use TrueLayer\Constants\CustomerSegments;
use TrueLayer\Constants\ReleaseChannels;
use TrueLayer\Entities\Entity;
use TrueLayer\Interfaces\Provider\ProviderFilterInterface;
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
    protected array $excludesProviderIds;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'countries',
        'release_channel',
        'customer_segments',
        'provider_ids',
        'excludes.provider_ids' => 'excludes_provider_ids',
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
            'excludes.provider_ids.*' => ['string'],
        ];
    }

    /**
     * @param string[] $countries
     *
     * @return ProviderFilterInterface
     */
    public function countries(array $countries): ProviderFilterInterface
    {
        $this->countries = $countries;

        return $this;
    }

    /**
     * @param string $releaseChannel
     *
     * @return ProviderFilterInterface
     */
    public function releaseChannel(string $releaseChannel): ProviderFilterInterface
    {
        $this->releaseChannel = $releaseChannel;

        return $this;
    }

    /**
     * @param string[] $customerSegments
     *
     * @return ProviderFilterInterface
     */
    public function customerSegments(array $customerSegments): ProviderFilterInterface
    {
        $this->customerSegments = $customerSegments;

        return $this;
    }

    /**
     * @param string[] $providerIds
     *
     * @return ProviderFilterInterface
     */
    public function providerIds(array $providerIds): ProviderFilterInterface
    {
        $this->providerIds = $providerIds;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getExcludesProviderIds(): array
    {
        return $this->excludesProviderIds ?? [];
    }

    /**
     * @param string[] $providerIds
     *
     * @return ProviderFilterInterface
     */
    public function excludesProviderIds(array $providerIds): ProviderFilterInterface
    {
        $this->excludesProviderIds = $providerIds;

        return $this;
    }
}
