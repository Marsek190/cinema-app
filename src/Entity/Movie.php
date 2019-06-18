<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\Table(name="movie")
 * @JMS\ExclusionPolicy("all")
 */
class Movie
{
    /**
     * @var int $id
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var string $title
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max="255")
     * @JMS\Expose
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @var int $year
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @JMS\SerializedName("year_release")
     * @JMS\Expose
     * @JMS\Type("string")
     */
    private $year;

    /**
     * @var ArrayCollection $tags
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="movies_tags")
     * @JMS\Expose
     * @JMS\Type("ArrayCollection<App\Entity\Tag>")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
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
     * @return Movie
     */
    public function setTitle(string $title): self
    {
        $this->title = strtolower($title);

        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     * @return Movie
     */
    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @param Tag $tag
     * @return Movie
     */
    public function addTag(Tag $tag): self
    {
        $tag->addMovie($this);
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * @param Tag $tag
     * @return Movie
     */
    public function deleteTag(Tag $tag): self
    {
        $tag->deleteMovie($this);
        $this->tags->removeElement($tag);
        return $this;
    }
}
