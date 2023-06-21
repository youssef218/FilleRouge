<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('reservation')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Groups('reservation')]
    private ?Evenemant $event = null;

    #[ORM\Column]
    #[Groups('reservation')]
    private ?\DateTimeImmutable $creatAt = null;

    #[ORM\Column]
    #[Groups('reservation')]
    private ?bool $prenstiel = null;
public function __construct(){
    $this->creatAt = new \DateTimeImmutable();
}
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getEvent(): ?Evenemant
    {
        return $this->event;
    }

    public function setEvent(?Evenemant $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getCreatAt(): ?\DateTimeImmutable
    {
        return $this->creatAt;
    }

    public function setCreatAt(\DateTimeImmutable $creatAt): self
    {
        $this->creatAt = $creatAt;

        return $this;
    }

    public function isPrenstiel(): ?bool
    {
        return $this->prenstiel;
    }

    public function setPrenstiel(bool $prenstiel): self
    {
        $this->prenstiel = $prenstiel;

        return $this;
    }
}
