<?php

namespace App\Repository;

use App\Entity\Person;
use App\Entity\PersonLikeProduct;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonLikeProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonLikeProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonLikeProduct[]    findAll()
 * @method PersonLikeProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonLikeProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonLikeProduct::class);
    }

    /**
     * @param PersonLikeProduct $personLikeProduct
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(PersonLikeProduct $personLikeProduct)
    {
        $this->_em->persist($personLikeProduct);
        $this->_em->flush();
    }

    /**
     * @param PersonLikeProduct $personLikeProduct
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(PersonLikeProduct $personLikeProduct)
    {
        $this->_em->remove($personLikeProduct);
        $this->_em->flush();
    }
}
