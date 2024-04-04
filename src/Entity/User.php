<?php

namespace App\Entity;

use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ApiResource(formats={"json"})
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @Vich\Uploadable
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Groups("post:prog")
     * @ORM\Column(type="string", length=180, unique=true)
     *
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     *  @Groups("post:prog")
     * @Assert\NotBlank(message="champs vide")
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     */
    private $password;
   
    
    
    
    private $plainPassword;
   /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
   /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;

    
    }







    /**
     * @Assert\NotBlank(message="champs vide")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @Assert\NotBlank(message="champs vide")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @Assert\NotBlank(message="champs vide")
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_d_n;

    /**
     * @Assert\NotBlank(message="champs vide")
     * @Groups("post:prog")
     * @ORM\Column(type="string", length=255,nullable=true)
     * 
     */
    private $username;

    /**
     * @Assert\NotBlank(message="champs vide")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sexe;

    /**
     * @Assert\NotBlank(message="champs vide")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tel_num;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img_name;
    /**
     * @Vich\UploadableField(mapping="User_images", fileNameProperty="img_name")
     * @var File
     */
    private $imageFile;
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     * @Gedmo\Timestampable(on="update")

     */
    private $updatedAt;
    /**
     * @Assert\NotBlank(message=" adresse doit etre non vide")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disable= false;


    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDateDN(): ?\DateTimeInterface
    {
        return $this->date_d_n;
    }

    public function setDateDN(?\DateTimeInterface $date_d_n): self
    {
        $this->date_d_n = $date_d_n;

        return $this;
    }
    public function getusernamee(): ?string
    {
        return $this->username;
    }
    public function setusername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getTelNum(): ?int
    {
        return $this->tel_num;
    }

    public function setTelNum(?int $tel_num): self
    {
        $this->tel_num = $tel_num;

        return $this;
    }

    public function getImgName(): ?string
    {
        return $this->img_name;
    }

    public function setImgName(?string $img_name): self
    {
        $this->img_name = $img_name;

        return $this;
    }
    public function setImageFile(File $img_name = null)
    {
        $this->imageFile = $img_name;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($img_name) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }


    
    public function getdisable(): bool
    {
        return $this->disable;
    }

   
    public function setdisable(bool $disable): self
    {
        $this->disable = $disable;

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getFirstname();
    }

}
