<?php
/** Importing functionality. That is, turning the fetched excel data into our format and storing it. */

require_once "model.php";
require_once 'SimpleXLSX.php';

class Importer {
	public array $drinks = [];
	public DateTime $date;

	function __construct(string $file_contents)
	{
		$xlsx = SimpleXLSX::parseData($file_contents);
		if (!$xlsx->success()) throw new Exception($xlsx->error());

		$rows = $xlsx->rows();

		$this->date = $this->extract_date($rows[0][0]);

		$products_size = sizeof($rows) - 4;
		for ($i = 4; $i < $products_size + 4; $i++) {
			try {
				array_push($this->drinks, $this->parse_row($rows[$i]));
			} catch (Exception $ex) {
				//error_log($ex);
			}
		}
	}

	public function set_import_batch_id(int $id) {
		foreach ($this->drinks as $drink) {
			$drink->import_batch = $id;
		}
	}

	private function extract_date(string $value): DateTime {
		$date_matches = [];
		if (preg_match("/([0-9]{1,2}.[0-9]{1,2}.[0-9]{4})/", $value, $date_matches)) {
			return new DateTime($date_matches[0]);
		}
		throw new Exception("Couldn't parse the date!: " . $value);
	}

	private function parse_row(array $row): Drink {
		$drink = new Drink();
		$drink->number = intval($row[0]);
		$drink->name = $row[1];
		$drink->manufacturer = $row[2];

		$size_matches = [];
		if (preg_match("/([0-9]+(,[0-9]+)?) l/S", $row[3], $size_matches)) {
			$drink->size_in_milliliters = intval(floatval($size_matches[0]) * 1000);
		} else {
			throw new Exception("Unknown drink amount format for drink $drink->number");
		}
		
		$drink->price = intval(floatval($row[4]) * 100);
		$drink->price_per_liter = intval(floatval($row[5]) * 100);
		$drink->type = $row[8];
		$drink->origin = $row[12];
		$drink->vintage = intval($row[14]);
		$drink->promille = intval(floatval($row[21]) * 100);
		$drink->kcal_per_hundred_ml = intval($row[27]);

		//$drink->validate(true);
		return $drink;
	}
}

?>
