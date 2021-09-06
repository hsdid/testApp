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
 * @Route("/product/{id}/edit", name="edit_product", methods={"POST"})
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

    public function __invoke(Request $request, int $id)
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->json(['error' => 'cant updated product']);
        }
        $data = json_decode($request->getContent(),true);

        $data['publicDate'] = new \DateTime($data['publicDate']);

        $form = $this->createForm(ProductType::class, $product);
        $form->submit($data);

        if ($form->isValid()) {
            try {
                $this->productRepository->update();
            } catch (OptimisticLockException | ORMException $e) {
                return $this->json(['error' => 'cant updated product']);
            }

            return $this->json([
                'product' => $product->getName(),
                'message' => 'Success updated',
            ]);
        }

        $error = $this->formErrors->getErrors($form);

        return $this->json([
            'error' => $error,
        ]);
    }
}
