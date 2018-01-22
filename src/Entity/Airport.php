<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AirportRepository")
 */
class Airport
{
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
    private $city;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $country;

    /**
         * @ORM\Column(type="string", length=3, nullable=true)
         */
    private $iata;

    /**
         * @ORM\Column(type="string", length=4, nullable=true)
         */
    private $icao;

    /**
         * @ORM\Column(type="decimal", precision=11, scale=8, nullable=true)
         */
    private $latitude;

    /**
         * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
         */
    private $longitude;

    /**
         * @ORM\Column(type="integer", nullable=true)
         */
    private $altitude;

    /**
         * @ORM\Column(type="float", nullable=true)
         */
    private $timezone;

    /**
         * @ORM\Column(type="string", length=1, nullable=true)
         */
    private $dst;

    /**
         * @ORM\Column(type="string", nullable=true)
         */
    private $tz;


    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCity() {
        return $this->city;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getIata() {
        return $this->iata;
    }

    public function getIcao() {
        return $this->icao;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function getAltitude() {
        return $this->altitude;
    }

    public function getTimezone() {
        return $this->timezone;
    }

    public function getDst() {
        return $this->dst;
    }

    public function getTz() {
        return $this->tz;
    }

    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    public function setCountry($country) {
        $this->country = $country;

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

    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    public function setAltitude($altitude) {
        $this->altitude = $altitude;

        return $this;
    }

    public function setTimezone($timezone) {
        $this->timezone = $timezone;

        return $this;
    }

    public function setDst($dst) {
        $this->dst = $dst;

        return $this;
    }

    public function setTz($tz) {
        $this->tz = $tz;

        return $this;
    }
}
