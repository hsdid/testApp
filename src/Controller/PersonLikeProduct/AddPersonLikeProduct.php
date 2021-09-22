<?php

declare(strict_types=1);

namespace App\Controller\PersonLikeProduct;

use App\Entity\Person;
use App\Entity\PersonLikeProduct;
use App\Entity\Product;
use App\Helper\PersonLikeProduct\PersonLikeProductHelper;
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
 * @Route("/personLikeProduct/add", name="add_person_like_product")
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
     * @var PersonLikeProductHelper
     */
    private PersonLikeProductHelper $personLikeProductHelper;

    /**
     * AddPersonLikeProduct constructor.
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
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {

        if ($request->isMethod('POST')) {

            $data = $request->request->all();

            $person = $this->personRepository->find($data['personId']);
            $product = $this->productRepository->find($data['productId']);

            $exist = $this->personLikeProductHelper->isPersonLikeProductExist($person, $product);

            if (!$person || !$product || $exist) {
                $this->addFlash('error', 'Cant add like, make sure like doesnt exist');

                return $this->redirectToRoute('add_person_like_product');
            }

            $personLikeProduct = new PersonLikeProduct();
            $personLikeProduct->setProduct($product);
            $personLikeProduct->setPerson($person);

            try {
                $this->personLikeProductRepository->save($personLikeProduct);
            } catch (OptimisticLockException | ORMException $e) {
                $this->addFlash('error', 'Something went wrong');

                return $this->redirectToRoute('add_person_like_product');
            }

            $this->addFlash('success', 'Like Add Successfully');

            return $this->redirectToRoute('add_person_like_product');
        }

        $persons = $this->personRepository->findAll();
        $products = $this->productRepository->findAll();

        return $this->render(
            'like/new.html.twig',
            [
                'persons' => $persons,
                'products' => $products
            ]
        );
    }
}
