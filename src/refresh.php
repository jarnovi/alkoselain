<?php
/** Page meant for admins for refreshing the data. */

require_once "fetcher.php";
require_once "importer.php";
$db = require_once "db.php";

$db->migrate_db();

$file = fetch_xlxs();
$importer = new Importer($file);

//var_dump($importer);

$import_batch_id = $db->create_import_batch($importer->date);
assert($import_batch_id  != null);
$importer->set_import_batch_id($import_batch_id);
assert(sizeof($importer->drinks) > 0);
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
		<h1>Ok</h1>
		<p>Data päivitetty onnistuneesti.</p>
		<p>Siirry <a href="index.php">takaisin.</a></p>
	</main>
</body>
</html>
