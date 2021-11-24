<?php

namespace TrueLayer\Contracts\Models;

interface UserInterface
{
    /**
     * @return UserInterface
     */
    public function new(): UserInterface;

    /**
     * @param string $name
     *
     * @return UserInterface
     */
    public function name(string $name): UserInterface;

    /**
     * @param string $email
     *
     * @return UserInterface
     */
    public function email(string $email): UserInterface;

    /**
     * @param string $phone
     *
     * @return UserInterface
     */
    public function phone(string $phone): UserInterface;

    /**
     * @return array
     */
    public function toArray(): array;
}
