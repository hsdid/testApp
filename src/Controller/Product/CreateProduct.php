<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product;
use App\Form\FormErrors;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
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
     * @throws Exception
     */
    public function __invoke(Request $request): Response
    {
        $data = $request->request->all();
        $data['publicDate'] = new \DateTime($data['publicDate']);

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        if ($form->isValid()) {

            try {
                $this->productRepository->save($product);
            } catch (OptimisticLockException | ORMException $e) {
                $this->addFlash('error', 'Cant Create product');

                return $this->redirectToRoute('get_products_list');
            }

            $this->addFlash('success', 'Product Created Successfully');

            return $this->redirectToRoute('get_products_list');
        }

        $errors = $this->formErrors->getErrors($form);

        $this->addFlash('error', $errors);

        return $this->redirectToRoute('get_products_list');
    }
}
