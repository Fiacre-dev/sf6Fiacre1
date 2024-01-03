<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Personne;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Personne>
 *
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

//    /**
//     * @return Personne[] Returns an array of Personne objects
//     */
   public function findPersonnesByAgeInterval($ageMin,$ageMax)
   {
       $qb= $this->createQueryBuilder('p');
       $qb= $this->createQueryBuilder('p');
         return $qb ->getQuery() ->getResult() ;
   }

   public function statsPersonnesByAgeInterval($ageMin, $ageMax)
   {
        $qb =  $this->createQueryBuilder('p')
            -> select('avg(p.age) as ageMoyen, count(p.id) as nombrePersonne');
            $this ->addIntervalAge($qb ,$ageMin,$ageMax);
          return $qb->getQuery() ->getScalarResult();
   }
   private function addIntervalAge(QueryBuilder $qb, $ageMin, $ageMax)
   {
       $qb->andWhere('p.age >= :ageMin') ->andWhere('p.age <= :ageMax')->setParameters(['ageMin' => $ageMin, 'ageMax' => $ageMax]);
   }

//    public function countAll()
// {
//     return $this->createQueryBuilder('p')
//         ->select('COUNT(p.id)')
//         ->getQuery()
//         ->getSingleScalarResult();
// }


//    public function findOneBySomeField($value): ?Personne
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
