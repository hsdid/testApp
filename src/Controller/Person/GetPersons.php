<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GetPersons
 * @package App\Controller\Person
 * @Route("/person", name="get_persons_list", methods={"GET"})
 */
class GetPersons extends AbstractController
{
    /**
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    /**
     * GetPersons constructor.
     * @param PersonRepository $personRepository
     */
    public function __construct(
        PersonRepository $personRepository
    ) {
        $this->personRepository = $personRepository;
    }

    public function __invoke(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $persons = $this->personRepository->findAll();

        return $this->json([
            'persons' => $persons,
            'qty' => count($persons)
        ]);
    }
}
