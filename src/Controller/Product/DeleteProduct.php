<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Repository\ProductRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteProduct
 * @package App\Controller\Product
 * @Route("/product/{id}/delete", name="delete_product")
 */
class DeleteProduct extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * DeleteProduct constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param int $id
     */
    public function __invoke(int $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->json(['error' => 'cant remove person']);
        }

        try {
            $this->productRepository->delete($product);
        } catch (OptimisticLockException | ORMException $e) {
            return $this->json(['error' => 'cant remove person']);
        }

        return $this->json([
            'product' => $product,
            'success' => 'deleted succesfully'
        ]);

    }
}
