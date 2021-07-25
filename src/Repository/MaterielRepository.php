<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Materiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materiel[]    findAll()
 * @method Materiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    /*
     * Retourne les materiels suivant les critères de recherches
     * @param Array
     * @return Array
     */
    public function findBySearch($value)
    {
        $query = $this->createQueryBuilder('h');

        if (!empty($value['search']))
            $query->andWhere('h.title LIKE :val')
                  ->setParameter('val', '%'.$value['search'].'%');
        if (!empty($value['difficulty_min']) && !empty($value['difficulty_max']))
            $query->andWhere('h.difficulty BETWEEN :min AND :max')
                  ->setParameter('min', $value['difficulty_min'])
                  ->setParameter('max', $value['difficulty_max']);
        if (!empty($value['duration_min']) && !empty($value['duration_max']))
            $query->andWhere('h.duration BETWEEN :min AND :max')
                  ->setParameter('min', date('H:i:s',($value['duration_min']-1)*3600-60))
                  ->setParameter('max', date('H:i:s',($value['duration_max']-1)*3600-60));
        if (!empty($value['type']))
            $query->andWhere('h.type = :type')
                  ->setParameter('type', $value['type']);
        if (!empty($value['return_start_point']))
            $query->andWhere('h.return_start_point = :return_start_point')
                  ->setParameter('return_start_point', $value['return_start_point']);

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Materiel[] Returns an array of Materiel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Materiel
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
