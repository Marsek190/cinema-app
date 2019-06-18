<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @param $movieId
     * @param array $titles
     * @return Tag
     * @throws ORMException|\DomainException
     */
    public function createTagOnMovie($movieId, array $titles): ?Tag
    {
        $tags = $this->findAll();
        $movieRepo = $this->_em->getRepository(Movie::class);
        $movie = $movieRepo->find($movieId);
        $movieTagsTitles = $movie->getTags()->map(function (Tag $tag) {
            return $tag->getTitle();
        })->toArray();
        if (!$movie) {
            throw new \DomainException('Movie with id = '.$movieId.' was not found.');
        }
        $titlesTags = array_map(function (Tag $tag) {
            return $tag->getTitle();
        }, $tags);
        $tag = null;
        foreach ($titles as $title) {
            if (!in_array($title, $titlesTags)) {
                $tag = new Tag();
                $tag->setTitle($title);
                $movie->addTag($tag);
            } elseif (in_array($title, $movieTagsTitles)) {
                throw new \DomainException('Tag with title = '.$title.' is exists in movie with id = '.$movie->getId());
            } else {
                $position = array_search($title, $titlesTags);
                //print_r($tags[$position]->getTitle());
                $tag = $tags[$position];
                $movie->addTag($tag);
            }
        }
        $entityManager = $movieRepo->getEntityManager();
        try {
            //$entityManager->persist($movie);
            $entityManager->flush();
            return $tag;
        } catch (ORMException $e) {
            throw $e;
        }
    }

    /**
     * @param mixed $tagId
     * @param mixed $movieId
     * @return void
     * @throws ORMException|\DomainException
     */
    public function delete($movieId, $tagId): void
    {
        $tag = $this->find($tagId);
        $movie = $this->_em->getRepository(Movie::class)->find($movieId);
        if (!$tag) {
            throw new \DomainException('Movie\'s tag with id = '.$tagId.' was not found.');
        }
        if (!$movie) {
            throw new \DomainException('Movie with id = '.$movieId.' was not found.');
        }
        $tag->deleteMovie($movie);
        $entityManager = $this->getEntityManager();
        try {
            $entityManager->persist($tag);
            $entityManager->flush();

        } catch (ORMException $e) {
            throw $e;
        }
    }
}
