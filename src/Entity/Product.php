<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     */
    private string $info;

    /**
     * @ORM\Column(name="public_date", type="date")
     */
    private $publicDate;

    /**
     * all users that like product
     * @ORM\OneToMany(targetEntity="App\Entity\PersonLikeProduct", mappedBy="product")
     */
    private $likedProducts;

    public function __construct()
    {
        $this->likedProducts = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * @param string $info
     * @return $this
     */
    public function setInfo(string $info): self
    {
        $this->info = $info;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPublicDate(): ?\DateTimeInterface
    {
        return $this->publicDate;
    }

    /**
     * @param \DateTimeInterface $publicDate
     * @return $this
     */
    public function setPublicDate(\DateTimeInterface $publicDate): self
    {
        $this->publicDate = $publicDate;

        return $this;
    }

    /**
     * @return Collection|PersonLikeProduct[]
     */
    public function getLikedProducts(): Collection
    {
        return $this->likedProducts;
    }

    public function addLikedProduct(PersonLikeProduct $likedProduct): self
    {
        if (!$this->likedProducts->contains($likedProduct)) {
            $this->likedProducts[] = $likedProduct;
            $likedProduct->setProduct($this);
        }

        return $this;
    }

    public function removeLikedProduct(PersonLikeProduct $likedProduct): self
    {
        if ($this->likedProducts->removeElement($likedProduct)) {
            // set the owning side to null (unless already changed)
            if ($likedProduct->getProduct() === $this) {
                $likedProduct->setProduct(null);
            }
        }

        return $this;
    }
}
