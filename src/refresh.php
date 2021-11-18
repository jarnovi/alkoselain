<?php
/** Page meant for admins for refreshing the data. */

require_once "fetcher.php";
require_once "importer.php";
$db =require_once "db.php";

$db->migrate_db();

$file = fetch_xlxs();
$drinks = xlxs_to_drinks($file);
$import_batch_id = $db->create_import_batch();
foreach ($drinks as $drink) {
	$drink->import_batch = $import_batch_id;
}

$db->add_drinks($drink);
?>


<!DOCTYPE html>
<html lang="fi">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AlkoSelain</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
	<main>
		<h1>Ok</h1>
		<p>Data päivitetty onnistuneesti.</p>
	</main>
</body>
</html>
