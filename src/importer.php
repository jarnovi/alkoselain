<?php
/** Importing functionality. That is, turning the fetched excel data into our format and storing it. */

require_once "model.php";
require_once 'SimpleXLSX.php';

function xlxs_to_drinks(string $file): array
{
	$xlsx = SimpleXLSX::parseData($file);
	if (!$xlsx->success()) throw new Exception($xlsx->error());

	$rows =  $xlsx->rows();
	$products_size = sizeof($rows) - 4;
	$drinks = [];

	for ($i = 4; $i < $products_size + 4; $i++) {
		//echo $tuotelista[$i][10]."<br>" ;

		// $numero,$nimi,$valmistaja,$pullokoko,$hinta,$litrahinta,$tyyppi,$valmistusmaa,$vuosikerta,$alkoholiprosentti,$energia

		$current_row = $rows[$i];

		// TODO: Propper type conversions

		try {
			$drink = new Drink();
			$drink->number = intval($current_row[0]);
			$drink->name = $current_row[1];
			$drink->manufacturer = $current_row[2];

			$size_matches = [];
			if (preg_match("/([0-9]+(,[0-9]+)?) l/S", $current_row[3], $size_matches)) {
				$drink->size_in_milliliters = intval(floatval($size_matches[0]) * 1000);
			} else {
				throw new Exception("Unknown drink amount format for drink $drink->number");
			}
			
			$drink->price = intval(floatval($current_row[4]) * 100);
			$drink->price_per_liter = intval(floatval($current_row[5]) * 100);
			$drink->type = $current_row[8];
			$drink->origin = $current_row[12];
			$drink->vintage = intval($current_row[14]);
			$drink->promille = intval(floatval($current_row[21]) * 100);
			$drink->kcal_per_hundred_ml = intval($current_row[27]);
			array_push($drinks, $drink);
		} catch (Exception $ex) {
			error_log($ex);
		}
	}

	return $drinks;
}

?>
