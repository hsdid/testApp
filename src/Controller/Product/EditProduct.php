<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Form\FormErrors;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditProduct
 * @package App\Controller\Product
 * @Route("/product/{id}/edit", name="edit_product")
 */
class EditProduct extends AbstractController
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
     * EditProduct constructor.
     * @param ProductRepository $productRepository
     * @param FormErrors $formErrors
     */
    public function __construct(
        ProductRepository $productRepository,
        FormErrors $formErrors
    ) {
        $this->productRepository = $productRepository;
        $this->formErrors = $formErrors;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Request $request, int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            $this->addFlash('error', 'The product cannot be found');
            return $this->redirectToRoute('get_products_list');
        }

        if ($request->isMethod('POST')) {

            $data = $request->request->all();
            $data['publicDate'] = new \DateTime($data['publicDate']);

            $form = $this->createForm(ProductType::class, $product);
            $form->submit($data);

            if ($form->isValid()) {
                try {
                    $this->productRepository->update();

                } catch (OptimisticLockException | ORMException $e) {

                    return $this->json(['error' => 'Product cannot be edited']);
                }
                $this->addFlash('success', 'Product edited successfully');

                return $this->redirectToRoute('get_products_list');
            }

            $error = $this->formErrors->getErrors($form);

            $this->addFlash('error', $error);

            return $this->redirectToRoute('edit_product');
        }

        return $this->render(
            '/product/edit.html.twig',
            [
                'product' => $product
            ]);
    }
}
