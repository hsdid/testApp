<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PersonLikeProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonLikeProductRepository::class)
 * @ORM\Table(name="person_like_product")
 */
class PersonLikeProduct
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="likedProducts")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=false)
     */
    private Person $person;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="likedProducts")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Product $product;

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person $person
     * @return $this
     */
    public function setPerson(Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
