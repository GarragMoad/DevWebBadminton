<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $nom = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $sigle = null;

    #[ORM\Column(length: 50)]
    private ?string $gymnase = null;

    #[ORM\Column(length: 50)]
    private ?string $adresse = null;

    /**
     * @var Collection<int, Reception>
     */
    #[ORM\OneToMany(targetEntity: Reception::class, mappedBy: 'club', orphanRemoval: true)]
    private Collection $receptions;

    /**
     * @var Collection<int, Equipe>
     */
    #[ORM\OneToMany(targetEntity: Equipe::class, mappedBy: 'club', orphanRemoval: true)]
    private Collection $equipe;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'club', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;



    public function __construct()
    {
        $this->receptions = new ArrayCollection();
        $this->equipe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): static
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getGymnase(): ?string
    {
        return $this->gymnase;
    }

    public function setGymnase(string $gymnase): static
    {
        $this->gymnase = $gymnase;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Reception>
     */
    public function getReceptions(): Collection
    {
        return $this->receptions;
    }

    public function addReception(Reception $reception): static
    {
        if (!$this->receptions->contains($reception)) {
            $this->receptions->add($reception);
            $reception->setClub($this);
        }

        return $this;
    }

    public function removeReception(Reception $reception): static
    {
        if ($this->receptions->removeElement($reception)) {
            // set the owning side to null (unless already changed)
            if ($reception->getClub() === $this) {
                $reception->setClub(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipe(): Collection
    {
        return $this->equipe;
    }

    public function addEquipe(Equipe $equipe): static
    {
        if (!$this->equipe->contains($equipe)) {
            $this->equipe->add($equipe);
            $equipe->setClub($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): static
    {
        if ($this->equipe->removeElement($equipe)) {
            if ($equipe->getClub() === $this) {
                $equipe->setClub(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
