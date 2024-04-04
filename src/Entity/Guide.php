<?php

namespace App\Entity;

use App\Repository\GuideRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GuideRepository::class)
 */
class Guide
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Assert\NotBlank(message="nom doit etre non vide")
     * @Assert\Length(
     *     min = 5,
     *     max=10,
     *     minMessage=" nom doit être  au moins de 5 caracteres"
     *
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $nom;


    /**
     * Assert\NotBlank(message="nom doit etre non vide")
     * @Assert\Length(
     *     min = 5,
     *     max=10,
     *     minMessage=" prenom doit être  au moins de 5 caracteres"
     *
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Assert\Email(
     *     message = "cet email '{{ value }}' n'est pas valide."
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Assert\Length(
     *     min = 8, max = 20,
     *     minMessage = "min_lenghth",
     *      maxMessage = "max_lenghth")
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }
}
