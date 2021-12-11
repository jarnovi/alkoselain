<?php
/** Shared models for the data */

/** Details about an import batch */
class ImportBatch {
	/** DB primary key */
	public int $id;
	/** An unix timestamp */
	public DateTime $date;
}

/** The main drink class. */
class Drink {
	/** The ID of the import batch, used as a part of the PK */
	public int $import_batch;
	/** The product number given to the drink by Alko */
	public int $number;
	/** The price of the product, in Euro Cents */
	public int $price;
	/** The price of a liter of the product, in Euro Cents */
	public int $price_per_liter;
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
	public ?int $vintage;
	/** The alcoholic ‰ (1/1000) of the drink. 10 ‰ = 1 % */
	public int $promille;
	/** The drink's kcal/100ml */
	public int $kcal_per_hundred_ml;

	/** Throws if the drink has invalid values. This is mostly just a sanity check of the data. */
	public function validate(bool $ignore_import_batch = false) {
		if(!$ignore_import_batch && !(is_int($this->import_batch) && $this->import_batch >= 0 && is_finite($this->import_batch)))
		throw new Exception("Drink import_batch is invalid");
		if(!(is_int($this->number) && $this->number > 0 && is_finite($this->number)))
		throw new Exception("Drink number is invalid");
		if(!(is_string($this->name) && strlen($this->name) > 0))
		throw new Exception("Drink name is invalid");
		if(!(is_string($this->manufacturer) && strlen($this->manufacturer) > 0))
		throw new Exception("Drink manufacturer is invalid");
		if(!(is_int($this->size_in_milliliters) && $this->size_in_milliliters > 0 && is_finite($this->size_in_milliliters)))
		throw new Exception("Drink size_in_milliliters is invalid");
		if(!(is_string($this->type) && strlen($this->type) > 0))
		throw new Exception("Drink type is invalid");
		if(!(is_int($this->price) && $this->price > 0 && is_finite($this->price)))
		throw new Exception("Drink price is invalid");
		if(!(is_int($this->price_per_liter) && $this->price_per_liter > 0 && is_finite($this->price_per_liter)))
		throw new Exception("Drink price_per_liter is invalid");
		if(!(is_string($this->origin) && strlen($this->origin) > 0))
		throw new Exception("Drink origin is invalid");
		if($this->vintage !== null && !(is_int($this->vintage) && $this->vintage > 0 && $this->vintage <= getdate()["year"])){
			// vintage value might not be given, if so set it to NULL
			$this->vintage = null;
		}
		// 10000 / 100 = 100 | 100 = max theoretical promilles in a drink
		if(!(is_int($this->promille) && $this->promille >= 0 && $this->promille <= 10000))
		throw new Exception("Drink promille is invalid");
		if(!(is_int($this->kcal_per_hundred_ml) && $this->kcal_per_hundred_ml >= 0  && is_finite($this->kcal_per_hundred_ml)))
		throw new Exception("Drink kcal_per_hundred_ml is invalid");
	}
}

?>
