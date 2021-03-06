<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $patronimic;

    #[ORM\Column(type: 'string', length: 255)]
    private $phone;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Reader::class, cascade: ['persist', 'remove'])]
    private $reader;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Librarian::class, cascade: ['persist', 'remove'])]
    private $librarian;

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
    public function getUserIdentifier(): string
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPatronimic(): ?string
    {
        return $this->patronimic;
    }

    public function setPatronimic(?string $patronimic): self
    {
        $this->patronimic = $patronimic;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getReader(): ?Reader
    {
        return $this->reader;
    }

    public function setReader(Reader $reader): self
    {
        // set the owning side of the relation if necessary
        if ($reader->getUser() !== $this) {
            $reader->setUser($this);
        }

        $this->reader = $reader;

        return $this;
    }

    public function getLibrarian(): ?Librarian
    {
        return $this->librarian;
    }

    public function setLibrarian(Librarian $librarian): self
    {
        // set the owning side of the relation if necessary
        if ($librarian->getUser() !== $this) {
            $librarian->setUser($this);
        }

        $this->librarian = $librarian;

        return $this;
    }

    
    public function __toString(): string
    {
        return $this->firstname.' '.$this->lastname.' '.$this->patronimic;
    }
}
