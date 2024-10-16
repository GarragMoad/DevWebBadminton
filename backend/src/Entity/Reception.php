<?php

namespace App\Entity;

use App\Repository\ReceptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceptionRepository::class)]
class Reception
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $jour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $horaire_debut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $horaire_fin = null;

    #[ORM\ManyToOne(targetEntity: TypeReception::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeReception $TypeReception = null;

    #[ORM\ManyToOne(targetEntity: Club::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $Club = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): static
    {
        $this->jour = $jour;

        return $this;
    }

    public function getHoraireDebut(): ?\DateTimeInterface
    {
        return $this->horaire_debut;
    }

    public function setHoraireDebut(\DateTimeInterface $horaire_debut): static
    {
        $this->horaire_debut = $horaire_debut;

        return $this;
    }

    public function getHoraireFin(): ?\DateTimeInterface
    {
        return $this->horaire_fin;
    }

    public function setHoraireFin(\DateTimeInterface $horaire_fin): static
    {
        $this->horaire_fin = $horaire_fin;

        return $this;
    }
    public function getIdTypeReception(): ?TypeReception
    {
        return $this->TypeReception;
    }

    public function setIdTypeReception(?TypeReception $idTypeReception): static
    {
        $this->idTypeReception = $idTypeReception;
        return $this;
    }

    public function getIdClub(): ?Club
    {
        return $this->Club;
    }

    public function setIdClub(?Club $idClub): static
    {
        $this->idClub = $idClub;
        return $this;
    }
}
