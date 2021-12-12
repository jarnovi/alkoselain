<?php

/** Page meant for admins for refreshing the data. */

require_once "fetcher.php";
require_once "importer.php";
$db = require_once "db.php";

$db->migrate_db();

$importer = new Importer(fetch_xlxs());

$import_batch_id = $db->create_import_batch($importer->date);
if ($import_batch_id == null) die("Couldn't create import batch!");
if (count($importer->drinks) >= 0) die("No drinks found!");

$importer->set_import_batch_id($import_batch_id);

$db->add_drinks($importer->drinks);
$db->set_import_batch_as_completed($import_batch_id);
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
		<p>Data päivitetty</p>
		<p>Siirry <a href="index.php">takaisin.</a></p>
	</main>
</body>

</html>
