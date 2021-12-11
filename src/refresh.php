<?php

/** Page meant for admins for refreshing the data. */

require_once "fetcher.php";
require_once "importer.php";
$db = require_once "db.php";

$db->migrate_db();

// In the future we could maybe write a controller class that can handle passing the URL + file path,
// Thus we could have one class controlling all these parts, but then we might need to use JS and buttons and form data.
$url = 'https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx';
$target = '../storage/alko-hinnasto.xlsx';
$target_bg = '../storage/alko-hinnasto_backup.xlsx';
$data = false;

if (!fetch_xlxs($url, $target)) {
    // fail, try to read data from backup
    if (!file_exists($target_bg)) {
        // fail, cannot update
        throw new Exception("Cannot read data!");
    } else {
        $importer = new Importer($target_bg);
    }
} else {
    $importer = new Importer($target);
}

if ($importer) {
    $data = true;
    $import_batch_id = $db->create_import_batch($importer->date);
    assert($import_batch_id  != null);
    $importer->set_import_batch_id($import_batch_id);
    assert(count($importer->drinks) > 0);


    $db->add_drinks($importer->drinks);
    $db->set_import_batch_as_completed($import_batch_id);
}
?>


<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlkoSelain</title>
    <link href="./style.css" rel="stylesheet" />
    <meta http-equiv="refresh" content="10; url=index.php">
</head>

<body>
    <main>
        <h1>Raportti</h1>
        <?= ($data ? "<p>Data päivitetty onnistuneesti.</p>" : "<p> Dataa ei saatu haettua! </p>") ?>
        <p>Siirry <a href="index.php">takaisin.</a></p>
    </main>
</body>

</html>
