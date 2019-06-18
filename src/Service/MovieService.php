<?php

namespace App\Service;

use App\Repository\MovieRepository;
use Doctrine\ORM\ORMException;

class MovieService
{
    /**
     * @var MovieRepository $movieRepository
    */
    protected $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @param array $filter
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findAll(array $filter = [])
    {
        $all = $this->movieRepository->findAllQuery($filter);
        $result = array();
        while (list($id, $title, $year, $tag_id, $tag_title) = $all->fetch()) {
            //echo $id;
            if (!array_key_exists($id, $result)) {
                $result[$id] = [
                    'id' => $id,
                    'year' => $year,
                    'title' => $title
                ];
            }
            $result[$id]['tags'][] = [
                'id' => $tag_id,
                'title' => $tag_title
            ];
        }
        //print_r($result);
        return array_key_exists('tag', $filter) ?
            array_filter($result, function ($item) use ($filter) {
                $input = array_flip(explode(',', $filter['tag']));
                $titles = array_flip(array_column($item['tags'], 'title'));
                return count(array_intersect_key($input, $titles)) == count($input);
            })
            : $result;
    }


    /**
     * @param array $data
     * @return void
     * @throws ORMException
     */
    public function create(array $data): void
    {

        $this->movieRepository->createMovie($data);
    }

    /**
     * @param mixed $id
     * @param array $data
     * @throws ORMException|\DomainException
     */
    public function edit($id, array $data): void
    {
        $this->movieRepository->editMovie($id, $data);
    }

    /**
     * @param mixed $id
     * @return void
     * @throws ORMException|\DomainException
     */
    public function delete($id): void
    {
        $this->movieRepository->deleteMovie($id);
    }
}