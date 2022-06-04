<?php

namespace App\Entity;

use App\Repository\LibraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryRepository::class)]
class Library
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $address;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'library', targetEntity: Librarian::class)]
    private $librarians;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'libraries')]
    private $books;

    #[ORM\OneToMany(mappedBy: 'library', targetEntity: Order::class)]
    private $orders;

    public function __construct()
    {
        $this->librarians = new ArrayCollection();
        $this->books = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Librarian>
     */
    public function getLibrarians(): Collection
    {
        return $this->librarians;
    }

    public function addLibrarian(Librarian $librarian): self
    {
        if (!$this->librarians->contains($librarian)) {
            $this->librarians[] = $librarian;
            $librarian->setLibrary($this);
        }

        return $this;
    }

    public function removeLibrarian(Librarian $librarian): self
    {
        if ($this->librarians->removeElement($librarian)) {
            // set the owning side to null (unless already changed)
            if ($librarian->getLibrary() === $this) {
                $librarian->setLibrary(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        $this->books->removeElement($book);

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setLibrary($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getLibrary() === $this) {
                $order->setLibrary(null);
            }
        }

        return $this;
    }
    
    public function __toString(): string
    {
        return $this->name;
    }
}
