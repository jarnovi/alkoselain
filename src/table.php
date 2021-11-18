<?php
/** Table HTML creation */

// TODO; Merge This file into the project properly and complete the displaying.

require_once "model.php";

class TableCreator {
	private const COLUMN_NAMES = array(
		"number" => "Numero",
		"name" => "Nimi"
	);

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
			$val .= "<tr>" . $column_to_display . "</tr>";
		}
		

		$val .= "</tr>";
		return $val;
	}

	private function create_filter_row(array $columns_to_display): string {
		$val = "<tr class='title'>
			<th class='small'><a href='index.php?jarjesta=1'>numero</a></th>
			<th><a href='index.php?jarjesta=2'>nimi</a></th>
			<th><a href='index.php?jarjesta=3'>valmistaja</a></th>
			<th class='small'><a href='index.php?jarjesta=4'>Pullokoko</a></th>
			<th class='small'><a href='index.php?jarjesta=5'>hinta</a></th>
			<th class='small'><a href='index.php?jarjesta=6'>litrahinta</a></th>
			<th><a href='index.php?jarjesta=7'>tyyppi</a></th>
			<th><a href='index.php?jarjesta=8'>valmistusmaa</a></th>
			<th class='small'><a href='index.php?jarjesta=9'>vuosikerta</a></th>
			<th class='small'><a href='index.php?jarjesta=10'>alkoholi-%</a></th>
			<th class='small'><a href='index.php?jarjesta=11'>energia</a></th>
		</tr>";
		return $val;
	}

	private function create_body_rows(array $columns_to_display, array $drinks): string {
		foreach ($drinks as $drink) {
			assert($drink instanceof Drink);

			$val .= "<tr>" . $column_to_display . "</tr>";
		}
		$val = "<tr>";

		$val .= "</tr>";

		return $val;
	}
}

return new TableCreator();
?>