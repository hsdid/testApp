<?php

declare(strict_types=1);

namespace App\Controller\PersonLikeProduct;

use App\Repository\PersonLikeProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GetPersonLikeProductList
 * @package App\Controller\PersonLikeProduct
 * @Route("/personLikeProduct", name="get_personLikeProduct_list", methods={"GET"})
 */
class GetPersonLikeProductList extends AbstractController
{
    /**
     * @var PersonLikeProductRepository
     */
    private PersonLikeProductRepository $personLikeProductRepository;

    /**
     * GetPersonLikeProductList constructor.
     * @param PersonLikeProductRepository $personLikeProductRepository
     */
    public function __construct(PersonLikeProductRepository $personLikeProductRepository)
    {
        $this->personLikeProductRepository = $personLikeProductRepository;
    }

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        $likes = $this->personLikeProductRepository->findAll();

        return $this->render(
            '/like/index.html.twig',
            [
                'likes' => $likes,
                'qty' => count($likes)
            ]);
    }
}