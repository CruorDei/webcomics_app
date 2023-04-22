<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
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

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function getProductByPageByCslug(int $page, string $slug, int $limit = 8): array
    // {
    //     $limit = abs($limit); //tjrs positive

    //     $result = [];
    //     // on suit une logique de requet sql SELECT x FROM y ... WHERE z
    //     $query = $this->getEntityManager()->createQueryBuilder()
    //         ->select('c', 'p') //'p' = produits et 'c' = category
    //         ->from('App\Entity\Product', 'p') // on defint p
    //         ->join('p.categories', 'c') //on definit c
    //         ->where("c.slug = '$slug'")
    //         ->setMaxResults($limit)
    //         ->setFirstResult(($page - 1) * $limit);

            
    //     $paginator = new Paginator($query);
    //     $data = $paginator->getQuery()->getResult();
        
    //     if(empty($data)){ return $result; }

    //     //nb pages
    //     $pages = ceil($paginator->count() / $limit);

    //     //
    //     $result['data'] = $data;
    //     $result['pages'] = $pages;
    //     $result['page'] = $page;
    //     $result['limit'] = $limit;

    //     return $result;
    // }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
