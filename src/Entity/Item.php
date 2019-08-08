<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
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
    private $item_name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemList", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $list;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemName(): ?string
    {
        return $this->item_name;
    }

    public function setItemName(string $item_name): self
    {
        $this->item_name = $item_name;

        return $this;
    }

    public function getList(): ?ItemList
    {
        return $this->list;
    }

    public function setList(?ItemList $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function getColorCode(): ?string
    {
        return $this->color_code;
    }

    public function setColorCode(?string $color_code): self
    {
        $this->color_code = $color_code;

        return $this;
    }

    public function getPlacement(): ?string
    {
        return $this->placement;
    }

    public function setPlacement(?string $placement): self
    {
        $this->placement = $placement;

        return $this;
    }
}