<?php

declare(strict_types=1);

namespace TrueLayer\Models;

use TrueLayer\Contracts\Models\UserInterface;

class User implements UserInterface
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $email = '';

    /**
     * @var string
     */
    private string $phone = '';

    public function new(): UserInterface
    {
        // TODO: 'what is existing'
        return $this;
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
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'type' => 'new', // TODO: move to constants
            'name' => $this->name,
        ];

        if (!empty($this->email)) {
            $data['email'] = $this->email;
        }

        if (!empty($this->phone)) {
            $data['phone'] = $this->phone;
        }

        return $data;
    }
}
