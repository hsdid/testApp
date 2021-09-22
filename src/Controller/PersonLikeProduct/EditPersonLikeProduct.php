<?php

declare(strict_types=1);

namespace App\Controller\PersonLikeProduct;

use App\Entity\PersonLikeProduct;
use App\Helper\PersonLikeProduct\PersonLikeProductHelper;
use App\Repository\PersonLikeProductRepository;
use App\Repository\PersonRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class EditPersonLikeProduct
 * @package App\Controller\PersonLikeProduct
 * @Route("/person/{person}/product/{product}/like/edit", name="edit_personlikeproduct")
 */
class EditPersonLikeProduct extends AbstractController
{
    /**
     * @var PersonLikeProductRepository
     */
    private PersonLikeProductRepository $personLikeProductRepository;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    /**
     * @var PersonLikeProductHelper
     */
    private PersonLikeProductHelper $personLikeProductHelper;

    /**
     * @param PersonLikeProductRepository $personLikeProductRepository
     * @param ProductRepository $productRepository
     * @param PersonRepository $personRepository
     * @param PersonLikeProductHelper $personLikeProductHelper
     */
    public function __construct(
        PersonLikeProductRepository $personLikeProductRepository,
        ProductRepository $productRepository,
        PersonRepository $personRepository,
        PersonLikeProductHelper $personLikeProductHelper
    )
    {
        $this->personLikeProductRepository = $personLikeProductRepository;
        $this->productRepository = $productRepository;
        $this->personRepository = $personRepository;
        $this->personLikeProductHelper = $personLikeProductHelper;
    }

    /**
     * @param Request $request
     * @param int $product
     * @param int $person
     * @return Response
     */
    public function __invoke(Request $request, int $product, int $person): Response
    {
        $persons = $this->personRepository->findAll();
        $products = $this->productRepository->findAll();

        $personLikeProduct = $this->personLikeProductRepository->findOneBy([
            'person' => $person,
            'product' => $product
        ]);


        if ($request->isMethod('POST')) {

            $data = $request->request->all();

            $person = $this->personRepository->find($data['personId']);
            $product = $this->productRepository->find($data['productId']);

            $exist = $this->personLikeProductHelper->isPersonLikeProductExist($person, $product);

            if ( !$person || !$product || $exist) {
                $this->addFlash('error', 'Cant edit like, make sure like already not exist');

                return $this->redirectToRoute('get_personLikeProduct_list');
            }

            $personLikeProduct->setPerson($person);
            $personLikeProduct->setProduct($product);

            try {
                $this->personLikeProductRepository->update();
            } catch (OptimisticLockException | ORMException $e) {
                $this->addFlash('error', 'Something went wrong');
                return $this->redirectToRoute('edit_personlikeproduct');
            }

            $this->addFlash('success', 'Like edited Successfully');
            return $this->redirectToRoute('get_personLikeProduct_list');

        }


        $exist = $this->personLikeProductHelper->isPersonLikeProductExist($person, $product);

        if ( !$exist) {
            $this->addFlash('error', 'Cant edit like, make sure like exist');

            return $this->redirectToRoute('get_personLikeProduct_list');
        }

        return $this->render(
            '/like/edit.html.twig',
            [
                'like' => $personLikeProduct,
                'persons' => $persons,
                'products' => $products
            ]);
    }
}