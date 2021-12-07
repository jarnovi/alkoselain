<?php
require_once "model.php";
$db = require "db.php";
$table_creator = require "table.php";
require_once "filter_query_generator.php";

$COLUMNS=["number","name","manufacturer","size","price","price_per_liter","type","origin","vintage","percentage","energy"];


$page = max(0, (int)($_GET['sivu'] ?? 0));
$amount = min(100, max(1, (int)($_GET['maara'] ?? 25)));

$min_price = $_GET["min-hinta"];
$max_price = $_GET["max-hinta"];
$min_size = $_GET['min-koko'];
$max_size = $_GET["max-koko"];
$type = $_GET["tyyppi"];
$origin = $_GET["maa"];

$db->migrate_db();
$latest_import_batch = $db->get_latest_import_batch();

$filter_query_generator = new FilterQueryGenerator($latest_import_batch->id);
$filter_query_generator->amount = $amount;
$filter_query_generator->start = $page*$amount;

$drinks = [];

if ($latest_import_batch) {
	$drinks = $db->fetch_drinks($filter_query_generator);
}
$table_html = $table_creator->create($COLUMNS, $drinks);
?>
<!DOCTYPE html>
<html lang="fi">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AlkoSelain/</title>
</head>
    <?php
        echo "<style>" . file_get_contents("./style.css") . "</style>";
    ?>
<body>
	<header>
        <div class="header-container">
            <h1>Alkon tuotekatalogi</h1>
            <!-- Maybe clean this up, or start using templates? -->
            <?php
            if ($latest_import_batch) {
                echo "<!--" . $latest_import_batch->id . "-->";
                echo "<div class='info-container'>
                        <p>:Päivitetty viimeksi: </p><br>
                           <time datetime='".$latest_import_batch->date->format("Y-m-d")."'>".
                    $latest_import_batch->date->format("d.m.Y")."
                           </time>
                      </div>";
            }   else {
                echo " <div class='warning-container'>
                       <p>Varoitus! Tietoja ei löytynyt.</p>
                       <p>Hae <br> <a href='./refresh.php' style='color:red;'>uudet tiedot</a></p>
                       <p> Saattaa ladata hetken, ole hyvä ja odota rauhassa :)</p>
                       </div>";
            }
            ?>
        </div>
	</header>

	<main>
        <form class='main-container' method="GET">
            <div class='table-container'>
                <?php echo $table_html; ?>
            </div>
            <div class='buttons-container'>
                <input type="hidden" name="maara" value="25">
                <button type="submit" name="sivu" value="<?= max(0, $page -1) ?>">Edellinen sivu</button>
                <button type="submit" name="sivu" value="<?= $page +1 ?>">Seuraava sivu</button>
            </div>
        </form>
	</main>
</body>
</html>
