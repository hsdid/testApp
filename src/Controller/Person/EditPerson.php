<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Form\FormErrors;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditPerson
 * @package App\Controller\Person
 * @Route("/person/{id}/edit", name="edit_person", methods={"POST"})
 */
class EditPerson extends AbstractController
{
    /**
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    /**
     * @var FormErrors
     */
    private FormErrors $formErrors;

    /**
     * EditPerson constructor.
     * @param PersonRepository $personRepository
     * @param FormErrors $formErrors
     */
    public function __construct(
        PersonRepository $personRepository,
        FormErrors $formErrors
    ) {
        $this->personRepository = $personRepository;
        $this->formErrors = $formErrors;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        $person = $this->personRepository->find($id);

        if (!$person) {
            return $this->json(['error' => 'cant updated person']);
        }

        $data = json_decode($request->getContent(),true);

        $form = $this->createForm(PersonType::class, $person);
        $form->submit($data);

        if ($form->isValid()) {
            try {
                $this->personRepository->update();
            } catch (OptimisticLockException | ORMException $e) {
                return $this->json(['error' => 'cant updated person']);
            }

            return $this->json([
                'person' => $person,
                'message' => 'Success updated',
            ]);
        }

        $error = $this->formErrors->getErrors($form);

        return $this->json([
            'error' => $error,
            'path' => 'src/Controller/PersonController.php',
        ]);
    }
}
