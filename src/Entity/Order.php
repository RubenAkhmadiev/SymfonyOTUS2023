<?php

namespace App\Entity;

use App\Backoffice\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $number;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $creation_date;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column]
    private float $sum;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'order')]
    private Collection $products;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getCreationDate(): \DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $userId): static
    {
        $this->user = $userId;

        return $this;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function setSum(float $sum): static
    {
        $this->sum = $sum;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addOrderId($this);
        }

        return $this;
    }

    public function removeItem(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeOrderId($this);
        }

        return $this;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'number' => $this->getNumber(),
            'products' => $this->products->toArray(),
            'creation_date' => $this->getCreationDate(),
            'sum' => $this->getSum(),
        ];
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
