<?php

class Filters
{
    private $filter = '';

    public function get_types(string $type = ""){

    }

    //SELECT distinct valmistusmaa from tuotteet
    // if argument is empty func returns all countries in the list
    public function get_made_in(string $country = ""){
        // if not given get all countries
        if($country == ""){
            /*
            $sqlCountries = $this->mysqli->prepare("SELECT DISTINCT (maa) FROM (allof) VALUES (?,?)");
            $maa = 'valmistusmaa';
            $allof = 'tuotteet';
            $sqlCountries->bind_param("is", $maa, $allof);
            $sqlCountries->execute();
            */
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            //$mysqlii = new mysqli("localhost", "root", "", "alkotaulu");
            // Do we need to prepare the statement here when the statement is hard coded?
            $result = $this->mysqli->query("SELECT distinct valmistusmaa from tuotteet");
            while ($data = $result->fetch_assoc()){
                $countries[] = $data;
            }
            return $countries;
        } else {
            /*
             *  We have to use queries for reading data from database
             *
            $sql = $this->mysqli->prepare("SELECT * FROM tuotteet WHERE valmistusmaa = ?;");
            $valmistusmaa = $country;
            $sql = $sql->bind_param('s', $valmistusmaa);
            $result = $sql->execute();
            */
            // maybe some sanitation here?
            $queryCountry = $country;
            $result = $this->mysqli->query("SELECT * FROM tuotteet WHERE valmistusmaa LIKE '$queryCountry'");
            while ($data = $result->fetch_assoc()){
                $oneCountysBeverages[] = $data;
            }

        }
    }

    // int?
    public function get_bottle_size(int $bottleSize){

    }

    public function get_price_between(int $min_price, int $max_price){

    }

    public function get_energy_between(int $min_energy, int $max_energy){

    }


    //SELECT distinct tyyppi from tuotteet
    // Might not need the string filter argument... not sure yet
    public function __construct(string $filter = "") {
    }
}

$filter = new Filters();

$filter->madeIn("ranska");
