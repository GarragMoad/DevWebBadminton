<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

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

    #[ORM\ManyToOne (targetEntity: Capitaine::class, inversedBy: 'equipes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Capitaine $capitaine = null;

    /**
     * @var Collection<int, Joueur>
     */
    #[ORM\ManyToMany(targetEntity: Joueur::class, inversedBy: 'equipes')]
    #[Assert\Valid()]
    private Collection $joueurs;

    #[ORM\Column(type: 'integer')]
    private float $score = 0.0;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
        for ($i = 0; $i <4 ; $i++) {
            $this->joueurs->add(new Joueur());
        }
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
     * @return Collection<int, Joueur>
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): self
    {
        if ($this->joueurs->count() < 4 && !$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
        }

        return $this;
    }

    public function removeJoueur(Joueur $joueur): static
    {
        $this->joueurs->removeElement($joueur);

        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }


    public function calculateScore(): void
    {
        $this->score = 0;
        foreach ($this->joueurs as $joueur) {
            $this->score += $joueur->getScore();
        }
    }


    /**
     * Validation pour s'assurer qu'il y a exactement 4 joueurs.
     * @Assert\Callback
     */
    public function validateJoueursCount(ExecutionContextInterface $context): void
    {
        if ($this->joueurs->count() !== 4) {
            $context->buildViolation('Vous devez créer exactement 4 joueurs.')
                ->atPath('joueurs')
                ->addViolation();
        }

        foreach ($this->joueurs as $joueur) {
            if (empty($joueur->getNom()) || empty($joueur->getPrenom())) {
                $context->buildViolation('Chaque joueur doit avoir un nom et un prénom.')
                    ->atPath('joueurs')
                    ->addViolation();
                break;
            }
        }
    }
}
