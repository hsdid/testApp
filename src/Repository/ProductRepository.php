<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param Product $product
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Product $product)
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function delete(Product $product)
    {
        $this->_em->remove($product);
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
            ->select('p.id, p.name, p.info, p.publicDate, count(l.person) as likes')
            ->leftJoin('p.likedProducts', 'l')
            ->groupBy('p.id')
            ->orderBy('likes', $order)->getQuery()->getResult();
    }

    /**
     * @param string $order
     * @return int|mixed|string
     */
    public function sortByDate(string $order)
    {
        return  $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.info, p.publicDate, count(l.person) as likes')
            ->leftJoin('p.likedProducts', 'l')
            ->groupBy('p.id')
            ->orderBy('p.publicDate', $order)->getQuery()->getResult();
    }
}
