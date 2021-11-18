<?php
/** Table HTML creation */

// TODO; Merge This file into the project properly and complete the displaying.

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
		"percentage" => "alkoholiprosentti",
		"energy" => "energia"
	];
	
	public function create(array $columns_to_display, array $drinks): string {
		
		// Sanity checks, make sure there are names for columns to display.
		if (count($columns_to_display) <= 0)
		throw new Exception("Error in configured columns to display: Need at least 1 column");
		foreach ($columns_to_display as $column_to_display) {
			assert($column_to_display instanceof string);
			if (!array_key_exists($column_to_display, TableCreator::COLUMN_NAMES))
			throw new Exception("Error in configured columns to display: Unknown column");
		}
		
		$val = "<table><thead>";
		$val .= $this->create_header_row($columns_to_display);
		$val .= $this->create_filter_row($columns_to_display);
		
		$val .= "</thead><tbody>";
		$val .= $this->create_body_rows($columns_to_display, $drinks);
		$val .= "</tbody></table>";
		
		return $val;
	}
	
	private function create_header_row(array $columns_to_display): string {
		$val = "<tr>";
		
		foreach ($columns_to_display as $column_to_display) {
			$val .= "<th>" . $column_to_display . "</th>";
		}
		
		
		$val .= "</tr>";
		return $val;
	}
	
	private function create_filter_row(array $columns_to_display): string {
		$val = "<tr>";
		
		foreach ($columns_to_display as $column_to_display) {
			$val .= "<th><input name='" . TableCreator::COLUMN_NAMES[$column_to_display] . "' placeholder='" . TableCreator::COLUMN_NAMES[$column_to_display] . "' type='text'/></th>";
		}
		
		return $val ."</tr>";
	}
	
	private function create_body_rows(array $columns_to_display, array $drinks): string {
		$val = "";
		foreach ($drinks as $drink) {
			assert($drink instanceof Drink);
			
			$val .= "<tr>";
			
			foreach ($columns_to_display as $column) {
				// TODO: create value-> string mappings for each possible value.
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
						$val .= $drink->size_in_milliliters;
						break;
					case "price":
						$val .= $drink->price;
						break;
					case "price_per_liter":
						$val .= $drink->price_per_liter;
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
						$val .= $drink->percentage;
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

return new TableCreator();
?>
