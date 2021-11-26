<?php

require_once 'SimpleXLSX.php';
require_once "importerV2.php";
$db = require "db.php";


function test(){
    // read data and save to file specified by $target
    $url = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx';
    $target= '../test/testhii.xlsx';
    file_put_contents($target, fopen($url, 'r'));


}

function parse(DB $db){
    $target= '../test/testhii.xlsx';
    //$xlsx = SimpleXLSX::parseD($target);
    //echo $xlsx;
    if ( $xlsx = SimpleXLSX::parse($target) ) {
           $rows = $xlsx->rows();
    }
    print_r($rows);
    gettype($rows);
    print_r(gettype($rows));

    $db->migrate_db();

    $importer = new ImporterV2($rows);

    //var_dump($importer);

    $import_batch_id = $db->create_import_batch($importer->date);
    assert($import_batch_id  != null);
    $importer->set_import_batch_id($import_batch_id);
    assert(count($importer->drinks) > 0);

    // maybe we could do the database imports in another thread
    // but if the school's server only has 1 core then it is not possible
    $drink_chunks = array_chunk($importer->drinks, 1024, true);
    $loopsMax = count($drink_chunks);
    for($i = 0; $i < $loopsMax; $i++){
        //$db->add_drinks($drink_chunks[$i], count($drink_chunks[$i]));
        $db->add_drinks($drink_chunks[$i]);
        print_r("Chunk " . $i+1 . " out of " . $loopsMax . " added to DB \n");
    }


    //$db->add_drinks($importer->drinks);
    $db->set_import_batch_as_completed($import_batch_id);

}


//test();



parse($db);





