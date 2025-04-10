<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $paidAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $paidBy = null;



    public function __construct(string $title, float $price, User $user, $description = null)
    {
        $this->verifyTitleLength($title);
        $this->verifyPriceAmount($price);

        $this->price = $price;
        $this->title = $title;
        $this->description = $description;
        $this->createdBy = $user;
        $this->createdAt = new \DateTime();
        $this->status = "PENDING";
    }

    public function pay(User $user) {

        if ($this->status !== "PENDING") {
            throw new \Exception("Invoice cannot be paid");
        }

        $this->status = "PAID";
        $this->paidAt = new \DateTime();
        $this->paidBy = $user;

    }


    private function verifyTitleLength(string $title) {
        if (strlen($title) < 5) {
            throw new \Exception("Le titre doit faire plus de trois caractères");
        }
    }

    private function verifyPriceAmount(float $price) {
        if ($price > 2000) {
            throw new \Exception("Le prix doit être inférieur à 2000");
        }

        if ($price < 0) {
            throw new \Exception("Le prix doit être supérieur à 0");
        }
    }

    public function update(string $title, float $price, string $description = null) {
        $this->verifyTitleLength($title);
        $this->verifyPriceAmount($price);

        $this->price = $price;
        $this->title = $title;
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): ?string
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getPaidBy(): ?User
    {
        return $this->paidBy;
    }

    public function setPaidBy(?User $paidBy): static
    {
        $this->paidBy = $paidBy;

        return $this;
    }
}
