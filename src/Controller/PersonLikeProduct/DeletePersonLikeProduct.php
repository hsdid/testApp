<?php

declare(strict_types=1);

namespace App\Controller\PersonLikeProduct;

use App\Entity\Person;
use App\Entity\Product;
use App\Repository\PersonLikeProductRepository;
use App\Repository\PersonRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeletePersonLikeProduct
 * @package App\Controller\PersonLikeProduct
 * @Route("/person/{personId}/product/{productId}/like/delete", name="delete_personLiekProduct")
 */
class DeletePersonLikeProduct extends AbstractController
{
    /**
     * @var PersonLikeProductRepository
     */
    private PersonLikeProductRepository $personLikeProductRepository;

    /**
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    public function __construct(
        PersonLikeProductRepository $personLikeProductRepository,
        PersonRepository $personRepository,
        ProductRepository $productRepository
    ) {
        $this->personLikeProductRepository = $personLikeProductRepository;
        $this->personRepository = $personRepository;
        $this->productRepository = $productRepository;
    }

    public function __invoke(int $personId, int $productId)
    {
        $person = $this->personRepository->find($personId);

        if (!$person) {
            return $this->json([
                'error' => 'person dosent exist'
            ]);
        }

        $product = $this->productRepository->find($productId);

        if (!$product) {
            return $this->json([
                'error' => 'product dosent exist'
            ]);
        }

        $likeProduct = $this->personLikeProductRepository->findOneBy([
            'person' => $person,
            'product' => $product
        ]);

        if (!$likeProduct) {
            return $this->json([
                'error' => 'Like dosent exist'
            ]);
        }

        try {
            $this->personLikeProductRepository->delete($likeProduct);
        } catch (OptimisticLockException | ORMException $e) {
            return $this->json([
                'error' => 'something went wrong'
            ]);
        }

        return $this->json([
            'person' => $person,
            'success' => 'deleted succesfully'
        ]);
    }
}
