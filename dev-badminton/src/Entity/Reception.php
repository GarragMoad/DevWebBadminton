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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $horaireDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $horaireFin = null;

    #[ORM\ManyToOne(inversedBy: 'receptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $club = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeReception $typeReception = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jours $jour = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getHoraireDebut(): ?\DateTimeInterface
    {
        return $this->horaireDebut;
    }

    public function setHoraireDebut(\DateTimeInterface $horaireDebut): static
    {
        $this->horaireDebut = $horaireDebut;

        return $this;
    }

    public function getHoraireFin(): ?\DateTimeInterface
    {
        return $this->horaireFin;
    }

    public function setHoraireFin(\DateTimeInterface $horaireFin): static
    {
        $this->horaireFin = $horaireFin;

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

    public function setTypeReception(?TypeReception $typeReception): self
    {
        $this->typeReception = $typeReception;

        return $this;
    }

    public function getTypeReception(): ?TypeReception
    {
        return $this->typeReception;
    }


    public function getJour(): ?jours
    {
        return $this->jour;
    }

    public function setJour(?jours $jour): static
    {
        $this->jour = $jour;

        return $this;
    }
}
