<?php

namespace App\Entity;

use App\Repository\VolsRepository;
use Cassandra\Date;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;
use Symfony\Component\Form\Extension\Core\Type\FileType;



/**
 * @ORM\Entity(repositoryClass=VolsRepository::class)
 */
class Vols
{
    /** @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("Vols")
     * @Groups("posts:read")
     */
    private $id;

    /**
     * @var string
     * Assert\NotBlank(message="destination doit etre non vide")
     * @Assert\Length(
     *     min = 5,
     *     minMessage=" destination doit être  au mini de 5 caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     * @Groups("Vols")
     * @Groups("posts:read")
     */
    private $destination_aller;

    /**
     * @var string
     * Assert\NotBlank(message="destination doit etre non vide")
     * @Assert\Length(
     *     min = 5,
     *     minMessage=" destination doit être  au mini de 5 caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     * @Groups("Vols")
     * @Groups("posts:read")
     */
    private $destination_retour;

    /**
     * @var string
     * Assert\NotBlank(message="voyage doit etre non vide")
     * @Assert\Length(
     *     min = 5,
     *     minMessage=" voyage doit être  au mini de 5 caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     * @Groups("Vols")
     * @Groups("posts:read")
     */
    private $voyage;

    /**
     * @var Date
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="date")
     * @Groups("Vols")
     * @Groups("posts:read")
     */
    private $date_depart;

    /**
     * @var Date
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     * @ORM\Column(type="date")
     * @Groups("Vols")
     * @Groups("posts:read")
     */
    private $date_retour;

    /**
     * @var String
     * Assert\NotBlank(message="passagers doit etre non vide")
     * @Assert\Length(
     *     min = 5,
     *     minMessage=" passagers doit être  au mini de 5 caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     * @Groups("Vols")
     * @Groups("posts:read")
     */
    private $passagers;

    /**
     * @var string
     * Assert\NotBlank(message="cabine doit etre non vide")
     * @Assert\Length(
     *     min = 3,
     *     minMessage=" cabine doit être au mini de 3 caracteres"
     * )
     * @ORM\Column(type="string", length=255)
     * @Groups("Vols")
     * @Groups("posts:read")
     */
    private $cabine;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestinationAller(): ?string
    {
        return $this->destination_aller;
    }

    public function setDestinationAller(string $destination_aller): self
    {
        $this->destination_aller = $destination_aller;

        return $this;
    }

    public function getDestinationRetour(): ?string
    {
        return $this->destination_retour;
    }

    public function setDestinationRetour(string $destination_retour): self
    {
        $this->destination_retour = $destination_retour;

        return $this;
    }

    public function getVoyage(): ?string
    {
        return $this->voyage;
    }

    public function setVoyage(string $voyage): self
    {
        $this->voyage = $voyage;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->date_retour;
    }

    public function setDateRetour(\DateTimeInterface $date_retour): self
    {
        $this->date_retour = $date_retour;

        return $this;
    }

    public function getPassagers(): ?string
    {
        return $this->passagers;
    }

    public function setPassagers(string $passagers): self
    {
        $this->passagers = $passagers;

        return $this;
    }

    public function getCabine(): ?string
    {
        return $this->cabine;
    }

    public function setCabine(string $cabine): self
    {
        $this->cabine = $cabine;

        return $this;
    }
}
