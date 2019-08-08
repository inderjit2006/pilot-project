<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemListRepository")
 */
class ItemList
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="itemLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="list", orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Priority", inversedBy="item_lists")
     */
    private $priorities;

    

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->priorities = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setList($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getList() === $this) {
                $item->setList(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Priority[]
     */
    public function getPriorities(): Collection
    {
        return $this->priorities;
    }

    public function addPriority(Priority $priority): self
    {
        if (!$this->priorities->contains($priority)) {
            $this->priorities[] = $priority;
        }

        return $this;
    }

    public function removePriority(Priority $priority): self
    {
        if ($this->priorities->contains($priority)) {
            $this->priorities->removeElement($priority);
        }

        return $this;
    }    
}
