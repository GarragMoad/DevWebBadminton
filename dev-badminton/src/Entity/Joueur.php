<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $nom = null;

    #[ORM\Column(length: 15)]
    private ?string $prenom = null;

    #[ORM\Column(length: 25)]
    private ?string $numreo_licence = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $classement_simple = null;

    #[ORM\Column(nullable: true)]
    private ?float $cpph_simple = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $classement_double = null;

    #[ORM\Column(nullable: true)]
    private ?float $cpph_double = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $classement_mixtes = null;

    #[ORM\Column(nullable: true)]
    private ?float $cpph_mixtes = null;

    #[ORM\ManyToMany(targetEntity: Equipe::class, mappedBy: 'joueurs')]
    private Collection $equipes;

    public function __construct()
    {
        $this->equipes = new ArrayCollection(); // Initialiser la propriété ici
    }
    public function getId(): ?int
    {
        return $this->id;
    }

     /**
     * @return Collection<int, Equipe>
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes[] = $equipe;
            $equipe->addJoueur($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->removeElement($equipe)) {
            $equipe->removeJoueur($this);
        }

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumreoLicence(): ?string
    {
        return $this->numreo_licence;
    }

    public function setNumreoLicence(string $numreo_licence): static
    {
        $this->numreo_licence = $numreo_licence;

        return $this;
    }

    public function getClassementSimple(): ?string
    {
        return $this->classement_simple;
    }

    public function setClassementSimple(string $classement_simple): static
    {
        $this->classement_simple = $classement_simple;

        return $this;
    }

    public function getCpphSimple(): ?float
    {
        return $this->cpph_simple;
    }

    public function setCpphSimple(?float $cpph_simple): static
    {
        $this->cpph_simple = $cpph_simple;

        return $this;
    }

    public function getClassementDouble(): ?string
    {
        return $this->classement_double;
    }

    public function setClassementDouble(?string $classement_double): static
    {
        $this->classement_double = $classement_double;

        return $this;
    }

    public function getCpphDouble(): ?float
    {
        return $this->cpph_double;
    }

    public function setCpphDouble(?float $cpph_double): static
    {
        $this->cpph_double = $cpph_double;

        return $this;
    }

    public function getClassementMixtes(): ?string
    {
        return $this->classement_mixtes;
    }

    public function setClassementMixtes(?string $classement_mixtes): static
    {
        $this->classement_mixtes = $classement_mixtes;

        return $this;
    }

    public function getCpphMixtes(): ?float
    {
        return $this->cpph_mixtes;
    }

    public function setCpphMixtes(?float $cpph_mixtes): static
    {
        $this->cpph_mixtes = $cpph_mixtes;

        return $this;
    }
}
