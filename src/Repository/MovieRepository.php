<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use DomainException;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * @param array $filter
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findAllQuery(array $filter = [])
    {
        //print_r($filter);
        $sortBy = array_diff_key($filter, array_flip(['page', 'search', 'tag']));
        $schema = "SELECT m.*, tg.id AS tag_id, tg.title AS tag_title FROM movie AS m INNER JOIN (SELECT t.*, mt.movie_id
            FROM tag AS t INNER JOIN movies_tags AS mt ON(t.id = mt.tag_id)) AS tg ON(m.id = tg.movie_id) WHERE m.id IN
            (SELECT m.id FROM movie AS m INNER JOIN movies_tags AS mt ON(m.id = mt.movie_id) WHERE mt.tag_id IN (SELECT id FROM tag AS t
            WHERE t.title IN ('триллер', 'ужасы', 'фэнтези'))) AND m.title LIKE '%а%' ORDER BY m.title DESC";
        $query = "SELECT m.*, tg.id AS tag_id, tg.title AS tag_title FROM movie AS m INNER JOIN (SELECT t.*, mt.movie_id 
            FROM tag AS t INNER JOIN movies_tags AS mt ON(t.id = mt.tag_id)) AS tg ON(m.id = tg.movie_id) WHERE m.id IN 
            (SELECT m.id FROM movie AS m INNER JOIN movies_tags AS mt ON(m.id = mt.movie_id)";
        if (array_key_exists('tag', $filter)) {
            $query = $query.' WHERE mt.tag_id IN (SELECT id FROM tag AS t WHERE t.title IN ('.'\''.implode('\', \'', explode(',', $filter['tag'])).'\''.'))';
        }
        $query = $query.')';
        if (array_key_exists('search', $filter)) {
            $query = $query.' AND m.title LIKE \'%'.$filter['search'].'%\'';
        }
        if ($sortBy) {
//            print_r($sortBy);
//            $ob = implode(', ', array_map(function ($key, $value) {
//                return 'm.'.$key.' '.$value;
//            }, array_keys($sortBy), $sortBy));
//            echo 'ORDER BY '. $ob;
//            $query = $query.' ORDER BY '.$ob;
            $query = $query.' ORDER BY m.'.key($sortBy).' '.current($sortBy);
        }
        //echo $query;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_NUM);
        $stmt->execute();

        return $stmt;
    }

    /**
     * @param array $data
     * @return void
     * @throws ORMException
    */
    public function createMovie(array $data = []): void
    {
        $tags = $this->_em->getRepository(Tag::class)->findAll();
        $titles = array_map(function (Tag $tag) {
            return $tag->getTitle();
        }, $tags);
        $movie = new Movie();
        $movie->setTitle($data['title']);
        $movie->setYear((int)$data['year']);
        foreach ($data['tags'] as $title) {
            if (!in_array($title, $titles)) {
                $tag = new Tag();
                $tag->setTitle($title);
                $movie->addTag($tag);
            } else {
                $position = array_search($title, $titles);
                $movie->addTag($tags[$position]);
            }
        }

        $entityManager = $this->getEntityManager();
        try {
            $entityManager->persist($movie);
            $entityManager->flush();
        } catch (ORMException $e) {
            throw $e;
        }
    }

    /**
     * @param array $data
     * @param mixed $id
     * @return void
     * @throws ORMException|DomainException
     */
    public function editMovie($id, array $data = []): void
    {
        $movie = $this->find($id);
        if (!$movie) {
            throw new DomainException('Movie with id = '.$id.' was not found.');
        }
        if (array_key_exists('title', $data)) {
            $movie->setTitle($data['title']);
        }
        if (array_key_exists('year', $data)) {
            $movie->setYear($data['year']);
        }
        $entityManager = $this->getEntityManager();
        try {
            //$entityManager->persist($movie);
            $entityManager->flush();

        } catch (ORMException $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @return void
     * @throws ORMException|DomainException
     */
    public function deleteMovie($id): void
    {
        $movie = $this->find($id);
        print_r($movie->getTitle());
        if (!$movie) {
            throw new DomainException('Movie with id = '.$id.' was not found.');
        }
        $entityManager = $this->getEntityManager();
        try {
            $entityManager->remove($movie);
            $entityManager->flush();

        } catch (ORMException $e) {
            throw $e;
        }
    }
}
