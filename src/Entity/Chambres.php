<?php

namespace App\Entity;

use App\Repository\ChambresRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ChambresRepository::class)
 */
class Chambres
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message=" type de chambre doit etre non vide")
     */
    private $typeChambre;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message=" nombre de lits doit etre non vide")
     * @Assert\Range(min=1 , max=4)
     */
    private $nbrLit;

    /**
     * @ORM\Column(type="text")
     *  @Assert\NotBlank(message="description est un champ obligatoir")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message=" description de chambre doit etre non vide")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Hotels::class, inversedBy="chambre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hotels;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeChambre(): ?string
    {
        return $this->typeChambre;
    }

    public function setTypeChambre(string $typeChambre): self
    {
        $this->typeChambre = $typeChambre;

        return $this;
    }

    public function getNbrLit(): ?int
    {
        return $this->nbrLit;
    }

    public function setNbrLit(int $nbrLit): self
    {
        $this->nbrLit = $nbrLit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

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


    public function getHotels(): ?Hotels
    {
        return $this->hotels;
    }

    public function setHotels(?Hotels $hotels): self
    {
        $this->hotels = $hotels;

        return $this;
    }
}
