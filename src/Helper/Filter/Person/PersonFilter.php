<?php

declare(strict_types=1);

namespace App\Helper\Filter\Person;

use App\Entity\Person;
use App\Repository\PersonRepository;

class PersonFilter
{
    /**
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    /**
     * PersonFilter constructor.
     * @param PersonRepository $personRepository
     */
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @param string|null $sort
     * @return array
     */
    public function getPersons(?string $sort): ?array
    {
        $persons = $this->personRepository->sortByLikes('desc');

        if ($sort == 'least-likes') {
            $persons = $this->personRepository->sortByLikes('asc');
        } else if ($sort == 'state-active') {
            $persons = $this->personRepository->findByState(Person::STATE_ACTIVE);
        } else if ($sort == 'state-banned') {
            $persons = $this->personRepository->findByState(Person::STATE_BANNED);
        } else if ($sort == 'state-removed') {
            $persons = $this->personRepository->findByState(Person::STATE_REMOVED);
        }

        return $persons;

    }
}
