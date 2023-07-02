<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, nullable: false)]
    private string $name;

    #[ORM\Column]
    private float $price;

    #[ORM\ManyToMany(targetEntity: Order::class, inversedBy: 'items')]
    private Collection $order;

    public function __construct()
    {
        $this->order = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrderId(): Collection
    {
        return $this->order;
    }

    public function addOrderId(Order $orderId): static
    {
        if (!$this->order->contains($orderId)) {
            $this->order->add($orderId);
        }

        return $this;
    }

    public function removeOrderId(Order $orderId): static
    {
        $this->order->removeElement($orderId);

        return $this;
    }
}
