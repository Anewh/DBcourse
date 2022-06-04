<?php

namespace App\Entity;

use App\Repository\LibrarianRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibrarianRepository::class)]
class Librarian
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $post;

    #[ORM\OneToOne(inversedBy: 'librarian', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;


    #[ORM\ManyToOne(targetEntity: Library::class, inversedBy: 'librarians')]
    private $library;

    public function __construct()
    {
        $this->support = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(?string $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getSupport(): Collection
    {
        return $this->support;
    }

    public function addSupport(Order $support): self
    {
        if (!$this->support->contains($support)) {
            $this->support[] = $support;
            $support->setLibrarian($this);
        }

        return $this;
    }

    public function removeSupport(Order $support): self
    {
        if ($this->support->removeElement($support)) {
            // set the owning side to null (unless already changed)
            if ($support->getLibrarian() === $this) {
                $support->setLibrarian(null);
            }
        }

        return $this;
    }

    public function getLibrary(): ?Library
    {
        return $this->library;
    }

    public function setLibrary(?Library $library): self
    {
        $this->library = $library;

        return $this;
    }

    public function __toString(): string
    {
        $user=$this->getUser();
        return $user->getName()." ".$user->getLastname()." ".$user->getPatronimic();
    }
}
