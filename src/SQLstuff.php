<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $tablename= "tuotteet";
  $dbname= "alkotaulu";

  function initSQLdb(){
    create_db();
    create_table();
  }

  function create_db(){
    global $servername,$username,$password,$dbname;
    // tehdään uusi tietokanta, mikäli ei ole jo olemassa
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    try {
        $conn = new PDO("mysql:host=$servername", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conn->exec($sql);
        } catch(PDOException $e) {
        echo "<p>Connection failed: " . $e->getMessage(). " </p>";
    }
  }

  function create_table(){
    global $servername,$username,$password,$dbname,$tablename;
    //tehdään uusi taulukko, mikäli ei ole olemassa.
    $sql = "CREATE TABLE IF NOT EXISTS $tablename (
      numero INT(8) UNSIGNED PRIMARY KEY,
      nimi VARCHAR(30) NOT NULL,
      valmistaja VARCHAR(30) NOT NULL,
      pullokoko VARCHAR(30) NOT NULL,
      hinta FLOAT NOT NULL,
      litrahinta FLOAT NOT NULL,
      tyyppi VARCHAR(32) NOT NULL,
      valmistusmaa VARCHAR(24) NOT NULL,
      vuosikerta YEAR(4) NOT NULL,
      alkoholiprosentti FLOAT NOT NULL,
      energia FLOAT NOT NULL
      )";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $conn->exec($sql);
        } catch(PDOException $e) {
        echo "<p>Connection failed: " . $e->getMessage(). " </p>";
    }
  }


  function SQLtop20($sort,$dir,$maara,$alku){
    if ($dir != "DESC") $dir = "ASC";
    global $servername,$username,$password,$dbname,$tablename;
    $sql = sprintf("SELECT * FROM %s ORDER BY %s %s LIMIT %s,%s;", $tablename, $sort, $dir,$alku,$maara);
    
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $retval = [];
      foreach ( $conn->query($sql) as $row){
        array_push($retval,$row);
      }
      return $retval;
  }

  function  SQLAddProduct($numero,$nimi,$valmistaja,$pullokoko,$hinta,$litrahinta,$tyyppi,$valmistusmaa,$vuosikerta,$alkoholiprosentti,$energia){
    global $servername,$username,$password,$dbname,$tablename;
    $sql= sprintf( "INSERT INTO %s VALUES( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');",
    $tablename,$numero,$nimi,$valmistaja,$pullokoko,$hinta,$litrahinta,$tyyppi,$valmistusmaa,$vuosikerta,$alkoholiprosentti,$energia);
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $conn->exec($sql);
      } catch(PDOException $e) {
      echo "<p>Connection failed: " . $e->getMessage(). " </p>";
  }

  }

?>