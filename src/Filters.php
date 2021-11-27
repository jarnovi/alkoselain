<?php

class Filters
{

    // TODO maybe get these elsewhere?
    // just 4 testing
    private string $servername = "localhost";
    private string $username = "root";
    private string $password = "";

    private $filter = '';
    private $mysqli;
    private $getters = [
        'types' => 'getTypes',
        'madeIn' => 'getMadeIn',
        'bottleSize' => 'getBottleSize',
        'priceBetween' => 'getPriceBetween',
        'EnergyBetween' => 'getEnergyBetween',
        // TODO add more ?
    ];

    private function getTypes(string $type = ""){

    }

    //SELECT distinct valmistusmaa from tuotteet
    // if argument is empty func returns all countries in the list
    private function getMadeIn(string $country = ""){
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
    private function getBottleSize(int $bottleSize){

    }

    private function getPriceBetween(Array $priceBetween){

    }

    private function getEnergyBetween(Array $energyBetween){

    }


    //SELECT distinct tyyppi from tuotteet
    // Might not need the string filter argument... not sure yet
    public function __construct(string $filter = "") {
        $this->filter = $filter;
        mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_STRICT);

        // TODO maybe something wiser
        $this->mysqli = new mysqli("localhost", "root", "", "alkotaulu" );
    }

    public function __call($method, $args) {
        if(!in_array($method, array_keys($this->getters))) {
            throw new BadMethodCallException();
        }
        echo $this->getters[$method];
        //array_unshift($args, $this->filter);
        //echo call_user_func_array([$this, $this->getters[$method]], $args);
        return call_user_func_array([$this, $this->getters[$method]], $args);

    }

}

$filter = new Filters();

$filter->madeIn("ranska");