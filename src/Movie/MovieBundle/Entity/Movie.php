<?php

namespace Movie\MovieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Movie
 * @package Movie\MovieBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="movie")
 */

class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $summary;

    /**
     * @ORM\Column(type="string", length=500)
     */
    protected $detailed_description;

    /**
     * @ORM\Column(type="datetime", options={"default" : NULL})
     */
    protected $date_added;

    /**
     * @ORM\Column(type="decimal", scale=1, precision=3)
     */
    protected $rating;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getDetailedDescription()
    {
        return $this->detailed_description;
    }

    /**
     * @param mixed $detailed_description
     */
    public function setDetailedDescription($detailed_description)
    {
        $this->detailed_description = $detailed_description;
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * @param mixed $date_added
     */
    public function setDateAdded($date_added)
    {
        $this->date_added = $date_added;
    }

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