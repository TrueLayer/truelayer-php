<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Contracts\Models\UserInterface;

class User implements UserInterface
{
    /**
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $email = null;

    /**
     * @var string|null
     */
    private ?string $phone = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return UserInterface
     */
    public function id(string $id = null): UserInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return UserInterface
     */
    public function name(string $name = null): UserInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return UserInterface
     */
    public function email(string $email = null): UserInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     *
     * @return UserInterface
     */
    public function phone(string $phone = null): UserInterface
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
        ];
    }

    /**
     * @param array $data
     *
     * @return UserInterface
     */
    public static function fromArray(array $data): UserInterface
    {
        return (new static())
            ->id($data['id'] ?? null)
            ->name($data['name'] ?? null)
            ->phone($data['phone'] ?? null)
            ->email($data['email'] ?? null);
    }
}
