<?php
/** Importing functionality. That is, turning the fetched excel data into our format and storing it. */

require_once "model.php";
require_once 'SimpleXLSX.php';

class ImporterV2 {
	public array $drinks = [];
	public DateTime $date;

	// public so we can later use these to display the values in correct format
	// or we could change the integers in model.php to floats
	public int $centsCon = 100;
	public int $litreCon = 1000;
	public int $promilCon = 100;

	// regex patters and replacements for clearing the bottle size in ml
	private $patterns = array();
	private $replacements = array();





	function __construct(Array $rows)
	{

		$this->patterns[0] = '/,/';
		$this->patterns[1] = '/([A-Z-a-z])/';
		$this->replacements[0] = '.';
		$this->replacements[1] = '';


		$this->date = $this->extract_date($rows[0][0]);

		$products_size = count($rows) - 4;
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
		$drink->number = (int)$row[0];
		$drink->name = $row[1];
		$drink->manufacturer = $row[2];
		// why not float? because it is bigger in size?
		$drink->size_in_milliliters = (int)((float)preg_replace($this->patterns, $this->replacements, $row[3])*$this->litreCon);

		/*
		$size_matches = [];
		if (preg_match("/([0-9]+(,[0-9]+)?) l/S", $row[3], $size_matches)) {
			$drink->bottle_size_l = (int)((float)$size_matches[0] * 1000);
		} else {
			throw new Exception("Unknown drink amount format for drink $drink->number");
		}
		*/

		$drink->price = (int)((float)$row[4] * $this->centsCon);
		$drink->price_per_liter = (int)((float)$row[5] * $this->centsCon);
		$drink->type = $row[8];
		$drink->origin = $row[12];
		$drink->vintage = (int)$row[14];
		$drink->promille = (int)((float)$row[21] * $this->promilCon);
		$drink->kcal_per_hundred_ml = (int)$row[27];

		$drink->validate(true);
		return $drink;
	}
}

?>
