<?php

namespace App\Repository;

use App\Entity\Component;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Component|null find($id, $lockMode = null, $lockVersion = null)
 * @method Component|null findOneBy(array $criteria, array $orderBy = null)
 * @method Component[]    findAll()
 * @method Component[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComponentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Component::class);
    }

    
    public function getVetted()
    {
    	return $this->createQueryBuilder('c')
    	->leftJoin('c.owner', 'o')
    	->andWhere('o.vetted = :vetted')
    	->setParameter('vetted', true)
    	->orderBy('c.name', 'ASC')
    	->getQuery()
    	->getResult()
    	;
    }
    
    // /**
    //  * @return Component[] Returns an array of Component objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Component
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
