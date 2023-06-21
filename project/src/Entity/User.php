<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups('user')]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups('user')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    #[Groups('user')]
    private ?string $teleportable = null;

    #[ORM\Column(length: 255)]
    #[Groups('user')]
    private ?string $adress = null;

    #[ORM\Column(length: 50)]
    #[Groups('user')]
    private ?string $cin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('user')]
    private ?string $nfiscale = null;

    #[ORM\Column(length: 50)]
    #[Groups('user')]
    private ?string $fullName = null;

    #[ORM\Column]
    #[Groups('user')]
    private ?\DateTimeImmutable $creatAt = null;

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Evenemant::class)]
    private Collection $evenemants;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Reservation::class)]
    private Collection $reservations;

    public function __construct(){
        $this->creatAt = new \DateTimeImmutable();
        $this->evenemants = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTeleportable(): ?string
    {
        return $this->teleportable;
    }

    public function setTeleportable(string $teleportable): self
    {
        $this->teleportable = $teleportable;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNfiscale(): ?string
    {
        return $this->nfiscale;
    }

    public function setNfiscale(?string $nfiscale): self
    {
        $this->nfiscale = $nfiscale;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

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

    /**
     * @return Collection<int, Evenemant>
     */
    public function getEvenemants(): Collection
    {
        return $this->evenemants;
    }

    public function addEvenemant(Evenemant $evenemant): self
    {
        if (!$this->evenemants->contains($evenemant)) {
            $this->evenemants->add($evenemant);
            $evenemant->setAdmin($this);
        }

        return $this;
    }

    public function removeEvenemant(Evenemant $evenemant): self
    {
        if ($this->evenemants->removeElement($evenemant)) {
            // set the owning side to null (unless already changed)
            if ($evenemant->getAdmin() === $this) {
                $evenemant->setAdmin(null);
            }
        }

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
            $reservation->setClient($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getClient() === $this) {
                $reservation->setClient(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return (string) $this->roles;
    }
}
