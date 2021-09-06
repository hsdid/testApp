<?php

declare(strict_types=1);

namespace App\Controller\Person;

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

        if (!$person) {
            return $this->json(['error' => 'cant remove person']);
        }

        try {
            $this->personRepository->delete($person);
        } catch (OptimisticLockException | ORMException $e) {
            return $this->json(['error' => 'cant remove person']);
        }

        return $this->json([
            'person' => $person,
            'success' => 'deleted succesfully'
        ]);
    }
}
