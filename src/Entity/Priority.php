<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriorityRepository")
 */
class Priority
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
    private $priority_name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ItemList", mappedBy="priorities")
     */
    private $item_lists;

    public function __construct()
    {
        $this->item_lists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriorityName(): ?string
    {
        return $this->priority_name;
    }

    public function setPriorityName(string $priority_name): self
    {
        $this->priority_name = $priority_name;

        return $this;
    }

    /**
     * @return Collection|ItemList[]
     */
    public function getItemLists(): Collection
    {
        return $this->item_lists;
    }

    public function addItemList(ItemList $itemList): self
    {
        if (!$this->item_lists->contains($itemList)) {
            $this->item_lists[] = $itemList;
            $itemList->addPriority($this);
        }

        return $this;
    }

    public function removeItemList(ItemList $itemList): self
    {
        if ($this->item_lists->contains($itemList)) {
            $this->item_lists->removeElement($itemList);
            $itemList->removePriority($this);
        }

        return $this;
    }
}
