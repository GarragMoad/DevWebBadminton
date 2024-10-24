<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $nom_equipe = null;

    #[ORM\Column(length: 10)]
    private ?string $numero_equipe = null;

    #[ORM\ManyToOne(inversedBy: 'equipe')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $club = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?capitaine $capitaine = null;

    /**
     * @var Collection<int, Joueur>
     */
    #[ORM\ManyToMany(targetEntity: Joueur::class)]
    private Collection $joueurs;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nom_equipe;
    }

    public function setNomEquipe(string $nom_equipe): static
    {
        $this->nom_equipe = $nom_equipe;

        return $this;
    }

    public function getNumeroEquipe(): ?string
    {
        return $this->numero_equipe;
    }

    public function setNumeroEquipe(string $numero_equipe): static
    {
        $this->numero_equipe = $numero_equipe;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }

    public function getCapitaine(): ?capitaine
    {
        return $this->capitaine;
    }

    public function setCapitaine(?capitaine $capitaine): static
    {
        $this->capitaine = $capitaine;

        return $this;
    }

    /**
     * @return Collection<int, joueur>
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(joueur $joueur): static
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
        }

        return $this;
    }

    public function removeJoueur(joueur $joueur): static
    {
        $this->joueurs->removeElement($joueur);

        return $this;
    }
}
