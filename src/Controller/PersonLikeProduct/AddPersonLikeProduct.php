<?php

declare(strict_types=1);

namespace App\Controller\PersonLikeProduct;

use App\Entity\Person;
use App\Entity\PersonLikeProduct;
use App\Entity\Product;
use App\Repository\PersonLikeProductRepository;
use App\Repository\PersonRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AddPersonLikeProduct
 * @package App\Controller\PersonLikeProduct
 * @Route("/personLikeProduct", name="add_person_like_product", methods={"POST"})
 */
class AddPersonLikeProduct extends AbstractController
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
     * AddPersonLikeProduct constructor.
     * @param PersonLikeProductRepository $personLikeProductRepository
     * @param PersonRepository $personRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        PersonLikeProductRepository $personLikeProductRepository,
        PersonRepository $personRepository,
        ProductRepository $productRepository
    ) {
        $this->personLikeProductRepository = $personLikeProductRepository;
        $this->personRepository = $personRepository;
        $this->productRepository = $productRepository;
    }


    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(),true);

        $person = $this->personRepository->find($data['person']);
        $product = $this->productRepository->find($data['product']);

        if (!$person || !$product) {
            return $this->json([
                'error' => 'cant like product'
            ]);
        }

        $personLikeProduct = new PersonLikeProduct();
        $personLikeProduct->setProduct($product);
        $personLikeProduct->setPerson($person);

        try {
            $this->personLikeProductRepository->save($personLikeProduct);
        } catch (OptimisticLockException | ORMException $e) {
            return $this->json([
                'error' => 'cant like product',
            ]);
        }

        return $this->json([
            'message' => 'Success',
            'like' => $personLikeProduct
        ]);
    }

}
