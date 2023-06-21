<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenemantRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EvenemantRepository::class)]
class Evenemant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('event')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('event')]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('event')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups('event')]
    private ?\DateTimeImmutable $debutAt = null;

    #[ORM\Column]
    #[Groups('event')]
    private ?\DateTimeImmutable $finAt = null;

    #[ORM\Column]
    #[Groups('event')]
    private ?\DateTimeImmutable $creatAt = null;

    #[ORM\Column]
    #[Groups('event')]
    private ?bool $isPublic = null;

    #[ORM\ManyToOne(inversedBy: 'evenemants')]
    private ?User $admin = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Reservation::class)]
    private Collection $reservations;
    public function __construct(){
        $this->creatAt = new \DateTimeImmutable();
        $this->reservations = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDebutAt(): ?\DateTimeImmutable
    {
        return $this->debutAt;
    }

    public function setDebutAt(\DateTimeImmutable $debutAt): self
    {
        $this->debutAt = $debutAt;

        return $this;
    }

    public function getFinAt(): ?\DateTimeImmutable
    {
        return $this->finAt;
    }

    public function setFinAt(\DateTimeImmutable $finAt): self
    {
        $this->finAt = $finAt;

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

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    public function setAdmin(?User $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setEvent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEvent() === $this) {
                $reservation->setEvent(null);
            }
        }

        return $this;
    }
}
