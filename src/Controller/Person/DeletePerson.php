<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeletePerson
 * @Route("/person/{id}/delete", name="delete_person")
 */
class DeletePerson extends AbstractController
{
    /**
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    /**
     * DeletePerson constructor.
     * @param PersonRepository $personRepository
     */
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @param int $id
     * @return Response
     */
    public function __invoke(int $id): Response
    {
        $person = $this->personRepository->find($id);

        if (!$person || $person->getState() == Person::STATE_REMOVED) {
            $this->addFlash('error', 'The person cannot be found or is already removed');
            return $this->redirectToRoute('get_persons_list');
        }

        try {
            $person->setState(Person::STATE_REMOVED);
            $this->personRepository->update();
            $this->addFlash('success', 'Person deleted successfully');

        } catch (OptimisticLockException | ORMException $e) {
            $this->addFlash('error', 'Something went wrong');

            return $this->redirectToRoute('get_persons_list');
        }

        return $this->redirectToRoute('get_persons_list');
    }
}
