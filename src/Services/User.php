<?php

declare(strict_types=1);

namespace TrueLayer\Services;

use TrueLayer\Constants\UserTypes;
use TrueLayer\Contracts\UserInterface;
use TrueLayer\Traits\HasAttributes;
use TrueLayer\Traits\WithSdk;

final class User implements UserInterface
{
    use WithSdk, HasAttributes;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->get('id');
    }

    /**
     * @param string|null $id
     *
     * @return UserInterface
     */
    public function id(string $id = null): UserInterface
    {
        return $this->set('id', $id);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->get('name');
    }

    /**
     * @param string|null $name
     *
     * @return UserInterface
     */
    public function name(string $name = null): UserInterface
    {
        return $this->set('name', $name);
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->get('email');
    }

    /**
     * @param string|null $email
     *
     * @return UserInterface
     */
    public function email(string $email = null): UserInterface
    {
        return $this->set('email', $email);
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->get('phone');
    }

    /**
     * @param string|null $phone
     *
     * @return UserInterface
     */
    public function phone(string $phone = null): UserInterface
    {
        return $this->set('phone', $phone);
    }

    /**
     * @return mixed[]
     * @throws \TrueLayer\Exceptions\ValidationException
     */
    public function toArray(): array
    {
        return \array_merge($this->validate(), [
            'type' => $this->getId() ? UserTypes::EXISTING : UserTypes::NEW,
        ]);
    }

    /**
     * @return mixed[]
     */
    private function rules(): array
    {
        return [
            'id' => 'string|nullable',
            'name' => 'string|required_without:id',
            'email' => 'string|nullable|required_without_all:phone,id',
            'phone' => 'string|nullable|required_without_all:email,id',
        ];
    }
}
