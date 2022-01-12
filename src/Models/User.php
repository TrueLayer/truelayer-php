<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Constants\UserTypes;
use TrueLayer\Contracts\UserInterface;

final class User extends Model implements UserInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $email;

    /**
     * @var string
     */
    protected string $phone;

    /**
     * @var string[]
     */
    protected array $arrayFields = [
        'id',
        'name',
        'email',
        'phone',
        'type',
    ];

    /**
     * @var string[]
     */
    protected array $rules = [
        'id' => 'string|nullable',
        'name' => 'string|nullable|required_without:id',
        'email' => 'string|nullable|email|required_without_all:phone,id',
        'phone' => 'string|nullable|required_without_all:email,id',
    ];

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ?? null;
    }

    /**
     * @param string $id
     *
     * @return UserInterface
     */
    public function id(string $id): UserInterface
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    /**
     * @param string $name
     *
     * @return UserInterface
     */
    public function name(string $name): UserInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    /**
     * @param string $email
     *
     * @return UserInterface
     */
    public function email(string $email): UserInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone ?? null;
    }

    /**
     * @param string $phone
     *
     * @return UserInterface
     */
    public function phone(string $phone): UserInterface
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->getId() ? UserTypes::EXISTING : UserTypes::NEW;
    }

    /**
     * @return mixed[]
     */
    public function all(): array
    {
        return \array_filter(parent::all());
    }
}
