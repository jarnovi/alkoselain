<?php
require_once "model.php";

class DB
{
	private $mysqli;

	function __construct()
	{
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"));
	}

	public function migrate_db()
	{
		$this->mysqli->begin_transaction();

		try {

			$this->mysqli->query(
				"CREATE TABLE IF NOT EXISTS import_batch (
					id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
					date DATETIME NOT NULL
				);"
			);

			$this->mysqli->query(
				"CREATE TABLE IF NOT EXISTS drink (
					import_batch INT NOT NULL,
					number INT NOT NULL,
					name TEXT NOT NULL,
					manufacturer TEXT NOT NULL,
					size_in_milliliters INT,
					type TEXT NOT NULL,
          price_in_cents INT NOT NULL,
          price_in_cents_per_liter INT NOT NULL,
					origin TEXT,
					vintage YEAR(4),
					promille INT,
					kcal_per_hundred_ml INT,
					PRIMARY KEY(number, import_batch),
					FOREIGN KEY(import_batch) REFERENCES import_batch(id)
				);"
			);



			$this->mysqli->commit();
		} catch (mysqli_sql_exception $exception) {
			$this->mysqli->rollback();
			throw $exception;
		}
	}

	public function get_latest_import_batch(): ?ImportBatch
	{
		$result = $this->mysqli->query("SELECT * FROM import_batch ORDER BY id DESC LIMIT 1");
		assert($result != false);
		$result = $result->fetch_object("ImportBatch");
		assert($result != false);
		return result;
	}

	function fetch_drinks($import_batch, $sort_by, $direction, $amount, $start)
	{
		if ($direction != "DESC") $direction = "ASC";

		$smtp = $this->mysqli->prepare("SELECT * FROM drink WHERE import_batch = ? ORDER BY ? ? LIMIT ? ?;");
		$smtp->bind_param("issii", $import_batch, $sort_by, $direction, $start, $amount);

		assert($smtp->execute());
		$result = $stmt->get_result();
		assert($result != false);

		$drinks = [];

		while ($drink = $result->fetch_object("Drink")) {
        array_push($drinks, $drink);
    }

		return $drinks;
	}

	function add_drinks(array $drinks)
	{
		$smtp = $this->mysqli->prepare(
			"INSERT INTO drink (
				import_batch, number, name, manufacturer, size_in_milliliters, type, price_in_cents,
				price_in_cents_per_liter, origin, vintage, promille, kcal_per_hundred_ml
			)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"
		);

		// TODO: Error Handling.

		foreach ($drinks as $drink) {
			assert($drink instanceof Drink);
			assert(is_int($drink->import_batch));
			assert(is_int($drink->number));
			assert(is_string($drink->name));
			assert(is_string($drink->manufacturer));
			assert(is_int($drink->size_in_milliliters));
			assert(is_string($drink->type));
			assert(is_int($drink->price));
			assert(is_int($drink->price_per_liter));
			assert(is_string($drink->origin));
			assert($drink->vintage instanceof DateTime);
			assert(is_int($drink->promille));
			assert(is_int($drink->kcal_per_hundred_ml));

			assert($smtp->bind_param(
				"iissisiisiii",
				$drink->import_batch,
				$drink->number,
				$drink->name,
				$drink->manufacturer,
				$drink->size_in_milliliters,
				$drink->type,
				$drink->price,
				$drink->price_per_liter,
				$drink->origin,
				$drink->vintage,
				$drink->propmille,
				$drink->kcal_per_hundred_ml,
			));
			assert($smtp->execute());
		}
	}

	function create_import_batch($date): int
	{
		$smtp = $this->mysqli->prepare("INSERT INTO import_batch (date) VALUES (?);");
		$smtp->bind_param("s", $date);

		assert($smtp->execute() != false);
		$id = $this->mysqli->insert_id;

		return $id;
	}
}

return new DB();
