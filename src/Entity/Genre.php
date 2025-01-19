<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 0,
        max: 10,
        notInRangeMessage: 'Vous devez choisir un chiffre entre {{ min }} et {{ max }}',
    )]
    private ?int $popularite = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $couleur = null;

    /**
     * @var Collection<int, Jeu>
     */
    #[ORM\OneToMany(targetEntity: Jeu::class, mappedBy: 'genre')]
    private Collection $jeux; //nouveau champ (relation & +l'infini )

    public function __construct()
    {
        $this->jeux = new ArrayCollection();
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

    public function getPopularite(): ?int
    {
        return $this->popularite;
    }

    public function setPopularite(int $popularite): static
    {
        $this->popularite = $popularite;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * @return Collection<int, Jeu>
     */
    public function getJeux(): Collection
    { // va permettre d'acquerie de nouveaux jeux et leur assigner un genre
        return $this->jeux;
    }

    public function addJeux(Jeu $jeux): static
    {
        if (!$this->jeux->contains($jeux)) {
            $this->jeux->add($jeux);
            $jeux->setGenre($this);
        }

        return $this;
    }

    public function removeJeux(Jeu $jeux): static
    {
        if ($this->jeux->removeElement($jeux)) {
            // set the owning side to null (unless already changed)
            if ($jeux->getGenre() === $this) {
                $jeux->setGenre(null);
            }
        }

        return $this;
    }
}
