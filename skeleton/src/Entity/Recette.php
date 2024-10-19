<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use App\Validator\BanWords;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Positive;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[UniqueEntity(fields: ['slug'])]
#[UniqueEntity(fields: ['titre'])]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:5)]
    #[BanWords( )]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:3, max:255)]
    #[Assert\Regex("/^[a-z0-9]+(?:-[a-z0-9]+)*$/")]
    private ?string $slug = null;

    #[ORM\Column(type: Types:: TEXT)]
    #[Assert\Length(min: 10)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive()]
    #[LessThan(value:1440)]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'recettes', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategory(): ?Categorie
    {
        return $this->category;
    }

    public function setCategory(?Categorie $category): static
    {
        $this->category = $category;

        return $this;
    }
}
