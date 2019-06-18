<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\ORMException;

class TagService
{
    /**
     * @var TagRepository $tagRepository
    */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param $movieId
     * @param array $titles
     * @return Tag
     * @throws ORMException|\DomainException
     */
    public function create($movieId, array $titles = []): Tag
    {
        return $this->tagRepository->createTagOnMovie($movieId, $titles);
    }

    /**
     * @param $movieId
     * @param $tagId
     * @throws ORMException|\DomainException
     */
    public function delete($movieId, $tagId): void
    {
        $this->tagRepository->delete($movieId, $tagId);
    }
}