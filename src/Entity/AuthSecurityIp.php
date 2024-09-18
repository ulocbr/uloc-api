<?php

namespace Uloc\ApiBundle\Entity;

class AuthSecurityIp
{
    private $id;

    private $date;

    private $ip;

    private $block;

    private $valid;
    private $expires;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function isBlock(): ?bool
    {
        return $this->block;
    }

    public function setBlock(bool $block): self
    {
        $this->block = $block;

        return $this;
    }

    /**
     * @return mixed
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param mixed $valid
     */
    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * @return mixed
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param mixed $expires
     */
    public function setExpires($expires): void
    {
        $this->expires = $expires;
    }
}
