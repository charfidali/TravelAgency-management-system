<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AvisRepository::class)
 */
class Avis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("avis")
     * @Groups("posts:read")
     *
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Hotels::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups("avis")
     * @Groups("posts:read")
     */
    private $hotel;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="avis" , cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups("avis")
     * @Groups("posts:read")
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * @Groups("avis")
     * @Groups("posts:read")
     */
    private $rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotel(): ?Hotels
    {
        return $this->hotel;
    }

    public function setHotel(?Hotels $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
