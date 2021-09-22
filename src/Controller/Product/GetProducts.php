<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Helper\Product\ProductFilter;
use App\Helper\Product\ProductHelper;
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
     * @var ProductHelper
     */
    private ProductHelper $productHelper;

    /**
     * GetProducts constructor.
     * @param ProductHelper $productHelper
     */
    public function __construct(ProductHelper $productHelper)
    {
        $this->productHelper = $productHelper;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $sort  = $request->query->get('sort');

        $products = $this->productHelper->getProducts($sort);

        return $this->render(
            '/product/index.html.twig',
            [
                'products' => $products
            ]);
    }
}
