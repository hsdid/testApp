<?php

declare(strict_types=1);

namespace App\Controller\PersonLikeProduct;

use App\Helper\Filter\PersonLikeProduct\PersonLikeProductHelper;
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

    /**
     * @var PersonLikeProductHelper
     */
    private PersonLikeProductHelper $personLikeProductHelper;

    /**
     * DeletePersonLikeProduct constructor.
     * @param PersonLikeProductRepository $personLikeProductRepository
     * @param PersonRepository $personRepository
     * @param ProductRepository $productRepository
     * @param PersonLikeProductHelper $personLikeProductHelper
     */
    public function __construct(
        PersonLikeProductRepository $personLikeProductRepository,
        PersonRepository $personRepository,
        ProductRepository $productRepository,
        PersonLikeProductHelper $personLikeProductHelper
    ) {
        $this->personLikeProductRepository = $personLikeProductRepository;
        $this->personRepository = $personRepository;
        $this->productRepository = $productRepository;
        $this->personLikeProductHelper = $personLikeProductHelper;
    }

    /**
     * @param int $personId
     * @param int $productId
     * @return Response
     */
    public function __invoke(int $personId, int $productId): Response
    {
        $person = $this->personRepository->find($personId);
        $product = $this->productRepository->find($productId);

        $exist = $this->personLikeProductHelper->isPersonLikeProductExist($person, $product);

        if (!$person || !$product || !$exist) {
            $this->addFlash('error', 'Cant remove like, make sure like doesnt exist');

            return $this->redirectToRoute('get_personLikeProduct_list');
        }

        $likeProduct = $this->personLikeProductRepository->findOneBy([
            'person' => $person,
            'product' => $product
        ]);

        try {
            $this->personLikeProductRepository->delete($likeProduct);
        } catch (OptimisticLockException | ORMException $e) {

            $this->addFlash('error', 'Like cannot be deleted');

            return $this->redirectToRoute('get_personLikeProduct_list');
        }

        $this->addFlash('success', 'Like deleted successfully');

        return $this->redirectToRoute('get_personLikeProduct_list');
    }
}
