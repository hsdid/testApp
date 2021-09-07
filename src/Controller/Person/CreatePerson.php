<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Entity\Person;
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
 * Class CreatePerson
 * @package App\Controller\Person
 * @Route("/person", name="create_person", methods={"POST"})
 */
class CreatePerson extends AbstractController
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
     * PersonController constructor.
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
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $data = $request->request->all();

        $person = new Person();

        $form = $this->createForm(PersonType::class, $person);
        $form->submit($data);

        if ($form->isValid()) {

            try {
                $this->personRepository->save($person);

            } catch (OptimisticLockException | ORMException $e) {

                $this->addFlash('error', 'Something went wrong');
                return $this->redirectToRoute('get_persons_list');
            }

            $this->addFlash('success', 'Person created successfully');

            return $this->redirectToRoute('get_persons_list');
        }

        $error = $this->formErrors->getErrors($form);

        $this->addFlash('error', $error);

        return $this->redirectToRoute('get_persons_list');
    }
}
