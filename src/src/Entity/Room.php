<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $invitation_code = null;

    #[ORM\Column]
    private ?int $member_capacity = null;

    #[ORM\Column(nullable: true)]
    private ?int $spy_member_id = null;

    #[ORM\Column]
    private ?bool $is_active = false;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    private String $currCapacity = "";

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $target_word = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getInvitationCode(): ?int
    {
        return $this->invitation_code;
    }

    public function setInvitationCode(int $invitation_code): self
    {
        $this->invitation_code = $invitation_code;

        return $this;
    }

    public function getMemberCapacity(): ?int
    {
        return $this->member_capacity;
    }

    public function setMemberCapacity(int $member_capacity): self
    {
        $this->member_capacity = $member_capacity;

        return $this;
    }

    public function getSpyMemberId(): ?int
    {
        return $this->spy_member_id;
    }

    public function setSpyMemberId(?int $spy_member_id): self
    {
        $this->spy_member_id = $spy_member_id;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getCurrCapacity(): string
    {
        return $this->currCapacity;
    }

    public function setCurrCapacity(string $currCapacity): void
    {
        $this->currCapacity = $currCapacity;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTargetWord(): ?string
    {
        return $this->target_word;
    }

    public function setTargetWord(?string $target_word): self
    {
        $this->target_word = $target_word;

        return $this;
    }

}
