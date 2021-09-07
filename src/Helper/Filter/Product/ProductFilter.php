<?php

declare(strict_types=1);

namespace App\Helper\Filter\Product;

use App\Repository\ProductRepository;

class ProductFilter
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**

     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param string|null $sort
     * @return array|null
     */
    public function getProducts(?string $sort): ?array
    {
        $products = $this->productRepository->sortByDate('DESC');

        if ($sort == 'least-likes') {
            $products = $this->productRepository->sortByLikes('ASC');
        } else if ($sort == 'likes') {
            $products = $this->productRepository->sortByLikes('DESC');
        } else if ($sort == 'oldest') {
            $products = $this->productRepository->sortByDate('ASC');
        }

        return $products;
    }
}
