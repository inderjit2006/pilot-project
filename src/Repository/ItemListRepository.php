<?php

namespace App\Repository;

use App\Entity\ItemList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ItemList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemList[]    findAll()
 * @method ItemList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ItemList::class);
    }

    // /**
    //  * @return ItemList[] Returns an array of ItemList objects
    //  */
    
    public function findByExampleField($user_id,$title)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.user_id = :user_id')
            ->andWhere('i.title = :title')
            ->setParameter('user_id', $user_id)
            ->setParameter('title', $title)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }



    

   
    public function findOneByUserIdAndTitle($user_id,$title): ?ItemList
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.user = :user_id')
            ->andWhere('i.title = :title')
            ->setParameter('user_id', $user_id)
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
