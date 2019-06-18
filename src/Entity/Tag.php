<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tag")
 * @JMS\ExclusionPolicy("all")
 */
class Tag
{
    /**
     * @var int $id
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @JMS\SerializedName("id")
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var string $title
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Assert\Type(type="string")
     * @JMS\SerializedName("title")
     * @JMS\Expose
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @var ArrayCollection $movies
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="tags", fetch="EXTRA_LAZY")
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getMovies()
    {
        return $this->movies;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Tag
     */
    public function setTitle(string $title): self
    {
        $this->title = strtolower($title);

        return $this;
    }

    /**
     * @param Movie $movie
     */
    public function addMovie(Movie $movie): void
    {
        $this->movies[] = $movie;
    }

    /**
     * @param Movie $movie
     */
    public function deleteMovie(Movie $movie): void
    {
        $this->movies->removeElement($movie);
    }
}
