<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatest(): array
    {
        return $this->createQueryBuilder('post')
            ->addSelect('comments', 'category')
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.category', 'category')

            ->orderBy('post.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneBySlug($slug): ?Post
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.slug = :slug')
            ->setParameter('slug', $slug)
            ->addSelect(['comments', 'category', 'user'])
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('comments.user', 'user')
            ->leftJoin('post.category', 'category')
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Post[] Returns an array of Post objects
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

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
