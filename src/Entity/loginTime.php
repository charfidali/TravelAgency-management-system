<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\loginTimeRepository;

/**
 * @ORM\Entity(repositoryClass=loginTimeRepository::class)
 * 
 */
class loginTime 
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $firstlogin;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastlogin;

    /**
     * @ORM\Column(type="string")
     */
    private $tokenverif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): object
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of firstlogin
     */ 
    public function getFirstlogin(): ?\DateTimeInterface
    {
        return $this->firstlogin;
    }

    /**
     * Set the value of firstlogin
     *
     * @return  self
     */ 
    public function setFirstlogin()
    {
        $this->firstlogin = new \DateTime('now', (new \DateTimeZone('Africa/Tunis')));
        return $this;
    }



    /**
     * Get the value of lastlogin
     */ 
    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    /**
     * Set the value of lastlogin
     *
     * @return  self
     */ 
    public function setLastlogin()
    {

       $this->lastlogin = new \DateTime('now');

        return $this;
    }
    
    /**
     * Get the value of tokenverif
     */ 
    public function getTokenverif()
    {
        return $this->tokenverif;
    }

    /**
     * Set the value of tokenverif
     *
     * @return  self
     */ 
    public function setTokenverif()
    {
        $this->tokenverif = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');

        return $this;
    }

    private $saved;


    /**
     * Get the value of saved
     */ 
    public function getSaved()
    {
        return $this->saved;
    }

    /**
     * Set the value of saved
     *
     * @return  self
     */ 
    public function setSaved($saved)
    {
        $this->saved = $saved;

        return $this;
    }
}
