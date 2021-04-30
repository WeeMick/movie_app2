<?php

namespace Movie\MovieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Class Movie
 * @package Movie\MovieBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="review")
 * @ExclusionPolicy("all")
 */

class Review
{

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setReviewId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * @param mixed $movie
     */
    public function setMovie(Movie $movie)
    {
        $movie->addReview($this);
        $this->movie = $movie;
    }

    /**
     * @return mixed
     */
    public function getReviewer()
    {
        return $this->reviewer;
    }

    /**
     * @param mixed $reviewer
     */
    public function setReviewer($reviewer)
    {
        $this->reviewer = $reviewer;
    }

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Expose
     */
    protected $id;

    /**
     * @ORM\ManyToOne( targetEntity="Movie\MovieBundle\Entity\Movie", inversedBy="movies")
     * @ORM\JoinColumn (name="movie_id", referencedColumnName="id", nullable=false)
     */
    protected $movie;

    /**
     * @var User
    * @ORM\ManyToOne( targetEntity="Movie\MovieBundle\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn (name="reviewer_id", referencedColumnName="id")
     */
    protected $reviewer;

    /**
     * @ORM\Column(type="string", length=700)
     */
    protected $review;

    /**
     * @ORM\Column(type="decimal", scale=1, precision=3)
     */
    protected $rating;

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }
}
