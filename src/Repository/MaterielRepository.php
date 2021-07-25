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
        $query = $this->createQueryBuilder('m');

        if (!empty($value['search']))
            $query->orWhere('m.nom LIKE :val')
                  ->orWhere('m.nom_court LIKE :val')
                  ->orWhere('m.reference_fabricant LIKE :val')
                  ->setParameter('val', '%'.$value['search'].'%');
        if (!empty($value['famille']))
            $query->andWhere('m.type = :type')
                  ->setParameter('type', $value['famille']);
        if (!empty($value['marque']))
            $query->andWhere('m.fabricant = :fabricant')
                  ->setParameter('fabricant', $value['marque']);

        return $query->getQuery()->getResult();
    }

}
