<?php

namespace Movie\MovieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Movie
 * @package Movie\MovieBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="review")
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
    public function getMovieId()
    {
        return $this->movie_id;
    }

    /**
     * @param mixed $movie_id
     */
    public function setMovieId($movie_id)
    {
        $this->movie_id = $movie_id;
    }

    /**
     * @return mixed
     */
    public function getReviewerId()
    {
        return $this->reviewer_id;
    }

    /**
     * @param mixed $reviewer_id
     */
    public function setReviewerId($reviewer_id)
    {
        $this->reviewer_id = $reviewer_id;
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
     */
    protected $id;

    /**
     * @ORM\ManyToOne( targetEntity="Movie\MovieBundle\Entity\Movie")
     * @ORM\JoinColumn (referencedColumnName="id")
     */
    protected $movie;

    /**
    * @ORM\ManyToOne( targetEntity="Movie\MovieBundle\Entity\User")
     * @ORM\JoinColumn (referencedColumnName="id")
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