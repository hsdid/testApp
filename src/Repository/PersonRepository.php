<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use const Grpc\STATUS_ABORTED;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @param Person $person
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Person $person)
    {
        $this->_em->persist($person);
        $this->_em->flush();
    }


    /**
     * @param Person $person
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(Person $person)
    {
        $person->setState(Person::STATE_REMOVED);
        $this->_em->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update()
    {
        $this->_em->flush();
    }

    /**
     * @param string $order
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function sortByLikes(string $order): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->select('p, count(*) as likes')
            ->leftJoin('person_like_product', 'l', 'ON', 'p.id = l.person_id')
            ->groupBy('p.login')
            ->orderBy('likes');

    }
}
