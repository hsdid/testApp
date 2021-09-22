<?php

declare(strict_types=1);

namespace App\Helper\PersonLikeProduct;

use App\Entity\Person;
use App\Entity\Product;
use App\Repository\PersonLikeProductRepository;

class PersonLikeProductHelper
{
    /**
     * @var PersonLikeProductRepository
     */
    private PersonLikeProductRepository $personLikeProductRepository;

    public function __construct(PersonLikeProductRepository $personLikeProductRepository)
    {
        $this->personLikeProductRepository = $personLikeProductRepository;
    }

    /**
     * @param $person
     * @param $product
     * @return bool
     */
    public function isPersonLikeProductExist($person, $product): bool
    {
        $personLikeProductExist = $this->personLikeProductRepository->findOneBy([
            'person' => $person,
            'product' => $product
        ]);

        if ($personLikeProductExist) {
            return true;
        }

        return false;
    }
}
