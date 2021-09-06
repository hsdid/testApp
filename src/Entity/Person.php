<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    public const STATE_ACTIVE = 1;

    public const STATE_BANNED = 2;

    public const STATE_REMOVED = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max=10)
     */
    private string $login;

    /**
     * @ORM\Column(name="l_name", type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max=100)
     */
    private string $lName;

    /**
     * @ORM\Column(name="f_name", type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max=100)
     */
    private string $fName;

    /**
     * @ORM\Column(type="smallint" , options={"unsigned"=true})
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    private int $state;

    /**
     * all liked products
     * @ORM\OneToMany(targetEntity="App\Entity\PersonLikeProduct", mappedBy="person")
     */
    private $likedProducts;

    public function __construct()
    {
        $this->likedProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getLName(): ?string
    {
        return $this->lName;
    }

    public function setLName(string $lName): self
    {
        $this->lName = $lName;

        return $this;
    }

    public function getFName(): ?string
    {
        return $this->fName;
    }

    public function setFName(string $fName): self
    {
        $this->fName = $fName;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

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
            $likedProduct->setPerson($this);
        }

        return $this;
    }

    public function removeLikedProduct(PersonLikeProduct $likedProduct): self
    {
        if ($this->likedProducts->removeElement($likedProduct)) {
            // set the owning side to null (unless already changed)
            if ($likedProduct->getPerson() === $this) {
                $likedProduct->setPerson(null);
            }
        }

        return $this;
    }
}
