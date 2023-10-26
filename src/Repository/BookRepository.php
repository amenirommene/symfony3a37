<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
    public function findBookByTitle($value1,$value2): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.author','a')
            ->andWhere('b.title = :val')
            ->andWhere('a.username = :name')
            ->setParameter('val', $value1)
            ->setParameter('name', $value2)
           ->orderBy('b.title', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
       ;
    }

    public function findBookByTitleDql($value1,$value2): array
    {
        $query=$this->getEntityManager()
        ->createQuery('SELECT b from App\Entity\Book b join App\Entity\Author a where b.title = :val and a.username = :name')
        ->setParameter('val',$value1)
        ->setParameter('name',$value2);
        return $query->getResult();
    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
