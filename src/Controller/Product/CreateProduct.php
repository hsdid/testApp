<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product;
use App\Form\FormErrors;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class CreateProduct
 * @package App\Controller\Product
 * @Route("/product", name="create_product", methods={"POST"})
 */
class CreateProduct extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;
    /**
     * @var FormErrors
     */
    private FormErrors $formErrors;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     * @param FormErrors $formErrors
     */
    public function __construct(
        ProductRepository $productRepository,
        FormErrors $formErrors
    )
    {
        $this->productRepository = $productRepository;
        $this->formErrors = $formErrors;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(),true);

        $product = new Product();
        $product->setPublicDate(new \DateTime());

        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        if ($form->isValid()) {

            $this->productRepository->save($product);

            return $this->json([
                'product' => $product->getName(),
                'message' => 'Product created succesfully',
            ]);
        }

        $errors = $this->formErrors->getErrors($form);

        return $this->json([
            'errors' => $errors,
            'message' => 'cant add product',
        ]);
    }
}
