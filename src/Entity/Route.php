<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RouteRepository")
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="airline_airports_unique_idx",
 *         columns={"airline_id", "from_airport_id", "to_airport_id"}
 *     )
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Route
{
    /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         */
    private $id;

    /**
         * @ORM\ManyToOne(targetEntity="Airport")
         * @ORM\JoinColumn(nullable=false)
         */
    private $fromAirport;

    /**
         * @ORM\ManyToOne(targetEntity="Airport")
         * @ORM\JoinColumn(nullable=false)
         */
    private $toAirport;

    /**
         * @ORM\ManyToOne(targetEntity="Airline", inversedBy="routes")
         * @ORM\JoinColumn(nullable=false)
         */
    private $airline;    
    
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

    public function getFromAirport() {
        return $this->fromAirport;
    }

    public function getToAirport() {
        return $this->toAirport;
    }

    public function getAirline() {
        return $this->airline;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function setFromAirport($airport) {
        $this->fromAirport = $airport;

        return $this;
    }

    public function setToAirport($airport) {
        $this->toAirport = $airport;

        return $this;
    }

    public function setAirline($airline) {
        $this->airline = $airline;

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
