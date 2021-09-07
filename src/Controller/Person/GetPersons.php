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

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        $persons = $this->personRepository->findAll();

        return $this->render(
            '/person/index.html.twig',
            [
                'msg' => '',
                'persons' => $persons,
                'qty' => count($persons)
            ]);
    }
}
