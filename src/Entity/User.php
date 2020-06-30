<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Mood::class, mappedBy="user")
     */
    private $mood;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="user", orphanRemoval=true)
     */
    private $booking;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="user", orphanRemoval=true)
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity=ToDoList::class, mappedBy="user", orphanRemoval=true)
     */
    private $todolist;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roles;

    public function __construct()
    {
        $this->mood = new ArrayCollection();
        $this->booking = new ArrayCollection();
        $this->note = new ArrayCollection();
        $this->todolist = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

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
     * @return (Role|string)[] The user roles
     */

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Mood[]
     */
    public function getMood(): Collection
    {
        return $this->mood;
    }

    public function addMood(Mood $mood): self
    {
        if (!$this->mood->contains($mood)) {
            $this->mood[] = $mood;
            $mood->setUser($this);
        }

        return $this;
    }

    public function removeMood(Mood $mood): self
    {
        if ($this->mood->contains($mood)) {
            $this->mood->removeElement($mood);
            // set the owning side to null (unless already changed)
            if ($mood->getUser() === $this) {
                $mood->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBooking(): Collection
    {
        return $this->booking;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->booking->contains($booking)) {
            $this->booking[] = $booking;
            $booking->setUser($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->booking->contains($booking)) {
            $this->booking->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getUser() === $this) {
                $booking->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNote(): Collection
    {
        return $this->note;
    }

    public function addNote(Note $note): self
    {
        if (!$this->note->contains($note)) {
            $this->note[] = $note;
            $note->setUser($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->note->contains($note)) {
            $this->note->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getUser() === $this) {
                $note->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ToDoList[]
     */
    public function getTodolist(): Collection
    {
        return $this->todolist;
    }

    public function addTodolist(ToDoList $todolist): self
    {
        if (!$this->todolist->contains($todolist)) {
            $this->todolist[] = $todolist;
            $todolist->setUser($this);
        }

        return $this;
    }

    public function removeTodolist(ToDoList $todolist): self
    {
        if ($this->todolist->contains($todolist)) {
            $this->todolist->removeElement($todolist);
            // set the owning side to null (unless already changed)
            if ($todolist->getUser() === $this) {
                $todolist->setUser(null);
            }
        }

        return $this;
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

}
