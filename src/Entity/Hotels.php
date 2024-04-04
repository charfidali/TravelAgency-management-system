<?php

namespace App\Entity;

use App\Repository\HotelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=HotelsRepository::class)
 */
class Hotels
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message=" nom doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un nom au mini de 5 caracteres"
     *
     *     )
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="nombre d'étoile doit etre non vide")
     * @Assert\Range(min=1 , max=5)
     */
    private $nbetoiles;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\NotBlank(message="l'adressse est un champ obligatoir")
     */
    private $adresse;

    /**
     *  @Assert\NotBlank(message="description est un champ obligatoir")
     * @ORM\Column(type="text", length=255)
     */
    private $pointfort;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Chambres::class, mappedBy="hotels", orphanRemoval=true)
     */
    private $chambre;

    public function __construct()
    {
        $this->chambre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNbetoiles(): ?int
    {
        return $this->nbetoiles;
    }

    public function setNbetoiles(int $nbetoiles): self
    {
        $this->nbetoiles = $nbetoiles;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPointfort(): ?string
    {
        return $this->pointfort;
    }

    public function setPointfort(string $pointfort): self
    {
        $this->pointfort = $pointfort;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Chambres>
     */
    public function getChambre(): Collection
    {
        return $this->chambre;
    }

    public function addChambre(Chambres $chambre): self
    {
        if (!$this->chambre->contains($chambre)) {
            $this->chambre[] = $chambre;
            $chambre->setHotels($this);
        }

        return $this;
    }

    public function removeChambre(Chambres $chambre): self
    {
        if ($this->chambre->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getHotels() === $this) {
                $chambre->setHotels(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getNom();
    }
}
