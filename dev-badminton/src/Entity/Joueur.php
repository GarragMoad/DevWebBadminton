<?php

namespace App\Entity;

use App\Enum\Classement;
use App\Repository\JoueurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 15)]
    private ?string $nom = null;

    #[ORM\Column(length: 15)]
    private ?string $prenom = null;

    #[ORM\Column(length: 25)]
    private ?string $numreo_licence = null;

    #[ORM\Column(type: 'string',enumType: Classement::class)]
    private ?Classement $classement_simple = null;

    #[ORM\Column(nullable: true)]
    private ?float $cpph_simple = null;

    #[ORM\Column(type: 'string',enumType: Classement::class)]
    private ?Classement $classement_double = null;

    #[ORM\Column(nullable: true)]
    private ?float $cpph_double = null;

    #[ORM\Column(type: 'string',enumType: Classement::class)]
    private ?Classement $classement_mixtes = null;

    #[ORM\Column(nullable: true)]
    private ?float $cpph_mixtes = null;

    #[ORM\ManyToMany(targetEntity: Equipe::class, mappedBy: 'joueurs')]
    private Collection $equipes;

    public function __construct()
    {
        $this->equipes = new ArrayCollection(); // Initialiser la propriété ici
    }
    public function getId(): ?Uuid
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

    public function getClassementSimple(): ?Classement
    {
        return $this->classement_simple;
    }

    public function setClassementSimple(Classement $classement_simple): static
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

    public function getClassementDouble(): ?Classement
    {
        return $this->classement_double;
    }

    public function setClassementDouble(?Classement $classement_double): static
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

    public function getClassementMixtes(): ?Classement
    {
        return $this->classement_mixtes;
    }

    public function setClassementMixtes(?Classement $classement_mixtes): static
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
