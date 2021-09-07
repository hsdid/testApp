<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Helper\Filter\Person\PersonFilter;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GetPersons
 * @package App\Controller\Person
 * @Route("/person/", name="get_persons_list", methods={"GET"})
 */
class GetPersons extends AbstractController
{

    /**
     * @var PersonFilter
     */
    private PersonFilter $personFilter;

    /**
     * GetPersons constructor.
     * @param PersonFilter $personFilter
     */
    public function __construct(

        PersonFilter $personFilter
    )
    {
        $this->personFilter = $personFilter;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $sort = $request->query->get('sort');

        $persons = $this->personFilter->getPersons($sort);

        return $this->render(
            '/person/index.html.twig',
            ['persons' => $persons]);
    }
}
