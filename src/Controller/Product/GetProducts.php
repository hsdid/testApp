<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Repository\PersonRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GetProducts
 * @package App\Controller\Product
 * @Route("/product", name="get_products_list", methods={"GET"})
 */
class GetProducts extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * GetProducts constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render(
            '/product/index.html.twig',
            [
                'msg' => '',
                'products' => $products,
                'qty' => count($products)
            ]);
    }
}
