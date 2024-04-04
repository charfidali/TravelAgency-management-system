<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VoitureRepository::class)
 */
class Voiture
{

    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="le matricule doit etre non vide")
     * @Assert\Length(
     *      min = 7,
     *      max = 12,
     *      minMessage = "le matricule doit etre de la forme 3Tunis1 ",
     *      maxMessage = "le matricule doit etre de la forme 123Tunis1234" )
     * @ORM\Column(type="string", length=1000)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="la marque doit etre non vide")
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\NotBlank(message="le model doit etre non vide")
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="nombre des sièges doit etre non vide")
     * @Assert\Range(min=2 , max=8)
     */
    private $nbsieges;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="le prix est un champ obligatoir")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le prix est un champ obligatoir")
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getNbsieges(): ?int
    {
        return $this->nbsieges;
    }

    public function setNbsieges(int $nbsieges): self
    {
        $this->nbsieges = $nbsieges;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
