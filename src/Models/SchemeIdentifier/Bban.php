<?php

declare(strict_types=1);

namespace TrueLayer\Models\SchemeIdentifier;

use TrueLayer\Contracts\SchemeIdentifier\BbanInterface;
use TrueLayer\Models\Model;

final class Bban extends Model implements BbanInterface
{
    /**
     * @var string
     */
    protected string $bban;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'bban',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'bban' => 'required|alpha_num|max:30',
    ];

    /**
     * @return string
     */
    public function getBban(): string
    {
        return $this->bban;
    }

    /**
     * @param string $bban
     *
     * @return BbanInterface
     */
    public function bban(string $bban): BbanInterface
    {
        $this->bban = $bban;

        return $this;
    }
}
