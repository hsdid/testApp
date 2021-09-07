<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Helper\Filter\Product\ProductFilter;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GetProducts
 * @package App\Controller\Product
 * @Route("/product", name="get_products_list", methods={"GET"})
 */
class GetProducts extends AbstractController
{
    /**
     * @var ProductFilter
     */
    private ProductFilter $productFilter;

    /**
     * GetProducts constructor.
     * @param ProductFilter $productFilter
     */
    public function __construct(ProductFilter $productFilter)
    {
        $this->productFilter = $productFilter;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $sort  = $request->query->get('sort');

        $products = $this->productFilter->getProducts($sort);

        return $this->render(
            '/product/index.html.twig',
            [
                'products' => $products
            ]);
    }
}
