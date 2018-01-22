<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Destination;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AirlineRepository")
 */
class Airline
{
    public function __construct() {
        $this->destinations = new ArrayCollection();
    }

    /**
         * @ORM\Id
         * @ORM\GeneratedValue
         * @ORM\Column(type="integer")
         */
    private $id;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $name;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $alias;

    /**
         * @ORM\Column(type="string", length=2, nullable=true)
         */
    private $iata;

    /**
         * @ORM\Column(type="string", length=3, nullable=true)
         */
    private $icao;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $callsign;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $country;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $url;
    
    /**
         * @ORM\OneToMany(targetEntity="Destination", mappedBy="airline")
         */
    private $destinations;


    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function getIata() {
        return $this->iata;
    }

    public function getIcao() {
        return $this->icao;
    }

    public function getCallsign() {
        return $this->callsign;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getDestinations() {
        return $this->destinations;
    }

    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    public function setAlias($alias) {
        $this->alias = $alias;

        return $this;
    }

    public function setIata($iata) {
        $this->iata = $iata;

        return $this;
    }

    public function setIcao($icao) {
        $this->icao = $icao;

        return $this;
    }

    public function setCallsign($callsign) {
        $this->callsign = $callsign;

        return $this;
    }

    public function setCountry($country) {
        $this->country = $country;

        return $this;
    }

    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    public function addDestination(Destination $destination) {
        $this->destinations->add($destination);

        return $this;
    }

    public function removeDestination(Destination $destination) {
        $this->destinations->removeElement($destination);

        return $this;
    }
}
