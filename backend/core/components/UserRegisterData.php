<?php

namespace core\components;

class UserRegisterData
{
    private string $name;
    private string $email;
    private string $password;
    private ?string $invitationHash;

    public function __construct(string $name, string $email, string $password, ?string $invitationHash = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->invitationHash = $invitationHash;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getInvitationHash(): ?string
    {
        return $this->invitationHash;
    }
}
