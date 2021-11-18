<?php
/** Shared models for the data */

/** Details about an import batch */
class ImportBatch {
	/** DB primary key */
	public int $id;
	/** An unix timestamp */
	public int $date;
}

/** The main drink class. */
class Drink {
	/** The ID of the import batch, used as a part of the PK */
	private int $import_batch;
	/** The product number given to the drink by Alko */
	public int $number;
	/** The name of the drink */
	public string $name;
	/** The name of the drink's manufacturer */
	public string $manufacturer;
	/** The size of the drink, in milliliters */
	public int $size_in_milliliters;
	/** The category of the drink */
	public string $type;
	/** The country where the drink was produced */
	public string $origin;
	/** The year in which the drink was produced */
	public int $vintage;
	/** The alcoholic % of the drink */
	public int $percentage;
	/** The drink's kcal/100ml */
	public int $kcal_per_hundred_ml;
}

?>
