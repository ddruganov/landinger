<?php

namespace core\social_network;

class SocialNetworkAuthClientData
{
    private string $social_id;
    private ?string $email = null;
    private ?string $name = null;
    private ?string $photo_link = null;

    public function getSocialId(): string
    {
        return $this->social_id;
    }
    public function setSocialId(string $value): self
    {
        $this->social_id = $value;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(?string $value): self
    {
        $this->email = $value;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $value): self
    {
        $this->name = $value;
        return $this;
    }

    public function getPhotoLink(): ?string
    {
        return $this->photo_link;
    }
    public function setPhotoLink(?string $value): self
    {
        $this->photo_link = $value;
        return $this;
    }
}
