<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\EquipeRepository::class)]
class Equipe
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $nom_equipe = null;

    #[ORM\Column(length: 25)]
    private ?string $numero_equipe = null;

    #[ORM\Column]
    private ?float $score = null;

    #[ORM\Column]
    private ?float $cpph = null;

    #[ORM\ManyToMany(targetEntity: Joueur::class, mappedBy: 'equipes')]
    private Collection $joueurs;

    #[ORM\ManyToOne(targetEntity: Capitaine::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Capitaine $capitaine = null;

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

    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): static
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
            $joueur->addEquipe($this);
        }
        return $this;
    }

    public function removeJoueur(Joueur $joueur): static
    {
        if ($this->joueurs->removeElement($joueur)) {
            $joueur->removeEquipe($this);
        }
        return $this;
    }

    public function getCapitaine(): ?Capitaine
    {
        return $this->capitaine;
    }

    public function setCapitaine(?Capitaine $capitaine): static
    {
        $this->capitaine = $capitaine;
        return $this;
    }

  
}