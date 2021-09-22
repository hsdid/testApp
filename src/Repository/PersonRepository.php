<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

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
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update()
    {
        $this->_em->flush();
    }

    /**
     * @param string $order
     * @return int|mixed|string
     */
    public function sortByLikes(string $order)
    {
        return  $this->createQueryBuilder('p')
            ->select('p.id, p.login, p.lName, p.fName, p.state , count(l.product) as likes')
            ->leftJoin('p.likedProducts', 'l')
            ->groupBy('p.id')
            ->orderBy('likes', $order)->getQuery()->getResult();
    }

    public function findByState(int $state)
    {
        return  $this->createQueryBuilder('p')
            ->select('p.id, p.login, p.lName, p.fName, p.state , count(l.product) as likes')
            ->leftJoin('p.likedProducts', 'l')
            ->where('p.state = :state')
            ->setParameter('state', $state)
            ->groupBy('p.id')
            ->orderBy('likes', 'desc')->getQuery()->getResult();
    }

    public function serachPerson(string $data)
    {
        return $this->createQueryBuilder('p')
            ->select('p.id, p.login, p.lName, p.fName, p.state')
            ->where('p.login LIKE :login')
            ->setParameter('login', "%$data%")
            ->getQuery()->getResult();
    }
}
