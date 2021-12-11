<?php

/** Table HTML creation based on the columns and drinks, as well as currently applied filters as the default values for the html form. */

require_once "model.php";

class TableCreator {
	private const COLUMN_NAMES = [
		"number" => "numero",
		"name" => "nimi",
		"manufacturer" => "valmistaja",
		"size" => "pullokoko",
		"price" => "hinta",
		"price_per_liter" => "litrahinta",
		"type" => "tyyppi",
		"origin" => "valmistusmaa",
		"vintage" => "vuosikerta",
		"percentage" => "alkoholi %",
		"energy" => "energia"
	];

	private array $columns_to_display;

	public null|int $min_size = null;
	public null|int $max_size = null;
	public null|int $min_price = null;
	public null|int $max_price = null;
	public null|string $type = null;
	public null|string $origin = null;
	public null|int $min_energy = null;
	public null|int $max_energy = null;

	public function __construct(array $columns_to_display)
	{
		// Sanity checks, make sure there are names for columns to display.
		if (count($columns_to_display) <= 0)
		throw new Exception("Error in configured columns to display: Need at least 1 column");
		foreach ($columns_to_display as $column_to_display) {
			assert(is_string($column_to_display));
			if (!array_key_exists($column_to_display, TableCreator::COLUMN_NAMES))
				throw new Exception("Error in configured columns to display, unknown column: '$column_to_display'");
		}

		$this->columns_to_display = $columns_to_display;
	}

	public function create(array $drinks): string
	{
		$val = "<table><thead>";
		$val .= $this->create_header_row();
		$val .= $this->create_filter_row();

		$val .= "</thead><tbody>";
		$val .= $this->create_body_rows($drinks);
		$val .= "</tbody></table>";

		return $val;
	}

	private function create_header_row(): string
	{
		$val = "<tr>";

		foreach ($this->columns_to_display as $column_to_display) {
			$val .= "<th>" . TableCreator::COLUMN_NAMES[$column_to_display] . "</th>";
		}


		$val .= "</tr>";
		return $val;
	}

	private function create_filter_row(): string
	{
		$val = "<tr>";

		foreach ($this->columns_to_display as $column) {
			$val .= "<th>";
			switch ($column) {
				case "size":
					$val .= "<input name='min-koko' type='number' min='0' step='0.1' placeholder='0.5L' value='" . $this->min_size / 1000 . "' />";
					$val .= "<input name='max-koko' type='number' min='0' step='0.1' placeholder='0.8L' value='" . $this->max_size / 1000 . "'  />";
					break;
				case "price":
					$val .= "<input name='min-hinta' type='number' min='0' step='0.01' placeholder='0.00€' value='" . $this->min_price / 100 . "' />";
					$val .= "<input name='max-hinta' type='number' min='0' step='0.01' placeholder='20.00€' value='" . $this->max_price / 100 . "' />";
					break;
				case "type":
					$val .= "<input name='tyyppi' type='text' placeholder='Oluet' value='$this->type' />";
					break;
				case "origin":
					$val .= "<input name='maa' type='text' placeholder='Suomi' value='$this->origin' />";
					break;
				case "energy":
					$val .= "<input name='min-energia' type='number' min='0' step='1' placeholder='0' value='" . $this->min_energy . "' />";
					$val .= "<input name='max-energia' type='number' min='0' step='1' placeholder='100' value='" . $this->max_energy . "' />";
					break;
			}

			$val .= "</th>";
		}

		return $val . "</tr>";
	}

	private function create_body_rows(array $drinks): string
	{
		$val = "";
		foreach ($drinks as $drink) {
			assert($drink instanceof Drink);

			$val .= "<tr>";

			foreach ($this->columns_to_display as $column) {
				$val .= "<th>";
				switch ($column) {
					case "number":
						$val .= $drink->number;
						break;
					case "name":
						$val .= $drink->name;
						break;
					case "manufacturer":
						$val .= $drink->manufacturer;
						break;
					case "size":
						$val .= $drink->size_in_milliliters / 1000 . "L";
						break;
					case "price":
						$val .= $drink->price / 100 . "€";
						break;
					case "price_per_liter":
						$val .= $drink->price_per_liter / 100 . "€";
						break;
					case "type":
						$val .= $drink->type;
						break;
					case "origin":
						$val .= $drink->origin;
						break;
					case "vintage":
						$val .= $drink->vintage;
						break;
					case "percentage":
						$val .= ($drink->promille / 10);
						break;
					case "energy":
						$val .= $drink->kcal_per_hundred_ml;
						break;
				}

				$val .= "</th>";
			}

			$val .= "</tr>";
		}

		return $val;
	}
}
