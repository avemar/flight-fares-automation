<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DestinationRepository")
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="airline_airport_unique_idx",
 *         columns={"airport_id", "airline_id"}
 *     )
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Destination
{
    /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         */
    private $id;    

    /**
         * @ORM\ManyToOne(targetEntity="Airport", inversedBy="destinations")
         * @ORM\JoinColumn(nullable=false)
         */
    private $airport;

    /**
         * @ORM\ManyToOne(targetEntity="Airline")
         * @ORM\JoinColumn(nullable=false)
         */
    private $airline;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $originXpath;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $destinationXpath;

    /**
         * @ORM\Column(type="datetime")
         */
    private $created;

    /**
         * @ORM\Column(type="datetime")
         */
    private $updated;


    public function getId() {
        return $this->id;
    }

    public function getAirport() {
        return $this->airport;
    }

    public function getAirline() {
        return $this->airline;
    }

    public function getOriginXpath() {
        return $this->originXpath;
    }

    public function getDestinationXpath() {
        return $this->destinationXpath;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function setAirport($airport) {
        $this->airport = $airport;

        return $this;
    }

    public function setAirline($airline) {
        $this->airline = $airline;

        return $this;
    }

    public function setOriginXpath($originXpath) {
        $this->originXpath = $originXpath;

        return $this;
    }

    public function setDestinationXpath($destinationXpath) {
        $this->destinationXpath = $destinationXpath;

        return $this;
    }

    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
         * @ORM\PrePersist
         */
    public function onCreation() {
        $this->setCreated(new \DateTime());
    }

    /**
         * @ORM\PrePersist
         * @ORM\PreUpdate
         */
    public function onUpdate() {
        $this->setUpdated(new \DateTime());
    }
}
