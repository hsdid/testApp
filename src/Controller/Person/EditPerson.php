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
 * @Route("/person/{id}/edit", name="edit_person")
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
    )
    {
        $this->personRepository = $personRepository;
        $this->formErrors = $formErrors;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function __invoke(Request $request, int $id): Response
    {
        $person = $this->personRepository->find($id);

        if (!$person) {
            $this->addFlash('error', 'The person cannot be found');
            return $this->redirectToRoute('get_persons_list');
        }

        if ($request->isMethod('POST')) {

            $data = $request->request->all();

            $form = $this->createForm(PersonType::class, $person);
            $form->submit($data);

            if ($form->isValid()) {
                try {
                    $this->personRepository->update();

                } catch (OptimisticLockException | ORMException $e) {
                    $this->addFlash('error', 'Something went wrong');

                    return $this->redirectToRoute('edit_person');
                }

                $this->addFlash('success', 'Person edited successfully');

                return $this->redirectToRoute('get_persons_list');
            }

            $error = $this->formErrors->getErrors($form);
            $this->addFlash('error', $error);

            return $this->redirectToRoute('edit_person');
        }

        return $this->render(
            '/person/edit.html.twig',
            [
                'person' => $person
            ]);

    }
}
