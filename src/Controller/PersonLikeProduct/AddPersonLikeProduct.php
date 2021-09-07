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

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {

        if ($request->isMethod('POST')) {

            $data = json_decode($request->getContent(),true);

            $person = $this->personRepository->find($data['person']);
            $product = $this->productRepository->find($data['product']);

            if (!$person || !$product) {
                $this->addFlash('error', 'Cant like');

                return $this->redirectToRoute('add_person_like_product');
            }

            $personLikeProductExist = $this->personLikeProductRepository->findOneBy([
                'person' => $person,
                'product' => $product
            ]);

            if ($personLikeProductExist) {
                var_dump('aa');
                die();
                $this->addFlash('error', 'Like exist');

                return $this->redirectToRoute('add_person_like_product');
            }

            $personLikeProduct = new PersonLikeProduct();
            $personLikeProduct->setProduct($product);
            $personLikeProduct->setPerson($person);

            try {
                $this->personLikeProductRepository->save($personLikeProduct);
            } catch (OptimisticLockException | ORMException $e) {
                $this->addFlash('error', 'Cant like');

                return $this->redirectToRoute('add_person_like_product');
            }

            $this->addFlash('success', 'Like Add Successfully');

            return $this->redirectToRoute('get_personLikeProduct_list');
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
