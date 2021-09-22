<?php
declare(strict_types=1);

namespace App\Controller\PersonLikeProduct;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/personLikeProduct/searchProduct", name="search_products", methods={"GET"})
 */
class SearchProducts extends AbstractController
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
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $product = $request->get('searchProduct');

        $products = $this->productRepository->serachProduct($product);

        return $this->json([
            'products' => $products
        ]);
    }
}
