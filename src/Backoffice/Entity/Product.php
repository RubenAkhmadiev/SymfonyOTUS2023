<?php

namespace App\Backoffice\Entity;

use App\Backoffice\Repository\ProductRepository;
use App\Entity\Order;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`product`', schema: 'backoffice')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'decimal', scale: 2)]
    private float $price;

    #[ORM\ManyToMany(targetEntity: Order::class, inversedBy: 'products')]
    private Collection $order;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinTable(name: 'product_category', schema: 'backoffice')]
    private Collection $categories;

    public function __construct()
    {
        $this->order = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'price' => $this->getPrice(),
        ];
    }
}
