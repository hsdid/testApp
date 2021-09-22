<?php
declare(strict_types=1);

namespace App\Controller\PersonLikeProduct;

use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/personLikeProduct/searchPerson", name="search_persons", methods={"GET"})
 */
class SearchPersons extends AbstractController
{
    /**
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    /**
     * @param PersonRepository $personRepository
     */
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $person = $request->get('searchPerson');

        $persons = $this->personRepository->serachPerson($person);

        return $this->json([
            'persons' => $persons
        ]);
    }
}
