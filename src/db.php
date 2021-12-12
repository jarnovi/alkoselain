<?php

/** This module is responsible for contacting the database. */

require_once "model.php";

class DB
{
    /** The only connection that shall be used in this project. */
    private mysqli $mysqli;

    function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->mysqli = new mysqli(getenv("DB_HOST"), getenv("DB_USER"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"));
    }

    public function migrate_db()
    {
		if (!$this->mysqli->begin_transaction()) throw new Exception("Couldn't start db transaction!");

        try {
            $dbname = getenv("DB_DATABASE");
            $result = $this->mysqli->query("CREATE DATABASE IF NOT EXISTS $dbname;");
			if ($result == false) throw new Exception("Couldn't create db!");

            $result = $this->mysqli->query(
                "CREATE TABLE IF NOT EXISTS import_batch (
                    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                    date DATETIME NOT NULL,
                    completed BOOLEAN NOT NULL DEFAULT FALSE
                );"
            );
			if ($result == false) throw new Exception("Couldn't create import_batch!");

            $result = $this->mysqli->query(
                "CREATE TABLE IF NOT EXISTS drink (
                    import_batch INT NOT NULL,
                    number INT NOT NULL,
                    name TEXT NOT NULL,
                    manufacturer TEXT NOT NULL,
                    size_in_milliliters INT,
                    type TEXT NOT NULL,
                    price INT NOT NULL,
                    price_per_liter INT NOT NULL,
                    origin TEXT NOT NULL,
                    vintage YEAR(4),
                    promille INT NOT NULL,
                    kcal_per_hundred_ml INT NOT NULL,
                    PRIMARY KEY(number, import_batch),
                    FOREIGN KEY(import_batch) REFERENCES import_batch(id)
                );"
            );
			if ($result == false) throw new Exception("Couldn't create drink!");

			if (!$this->mysqli->commit()) throw new Exception("Couldn't commit transaction!");
        } catch (mysqli_sql_exception $exception) {
            $this->mysqli->rollback();
            throw $exception;
        }
    }

    public function get_latest_import_batch(): ?ImportBatch
    {
        $result = $this->mysqli->query(
            "SELECT id, date FROM import_batch WHERE completed = true ORDER BY id DESC LIMIT 1"
        );
		if ($result == false) throw new Exception("Couldn't create query!");
        $result = $result->fetch_assoc();
		if ($result == false) throw new Exception("Couldn't fetch_assoc query result!");
        if ($result === null) return null;
        $import_batch = new ImportBatch();
        $import_batch->id = $result["id"];
        $import_batch->date = new DateTime($result["date"]);
        return $import_batch;
    }

    function fetch_drinks(FilterQueryGenerator $query): array
    {
        $sql = "SELECT * FROM drink WHERE ";
        $sql .= $query->get_where_clause_contents();

        $order_by = $query->order_by;
        if ($order_by != null) {
            $direction = $query->direction;
            if ($direction != "DESC") $direction = "ASC";
            $sql .= " ORDER BY " . $order_by . " " . $direction;
        }

        $sql .= " LIMIT " . $query->start . ", " . $query->amount . ";";

        $smtp = $this->mysqli->prepare($sql);
		if ($smtp == false) throw new Exception("Couldn't prepare smtp!");

        $param_types = $query->get_bind_param_types();
        $param_values = $query->get_bind_param_values();
        if ($param_types != null && $param_values != null) {
			if ($smtp->bind_param($param_types, ...$param_values) == false)
				throw new Exception("Couldn't bind_param!");
        }

		if ($smtp->execute() == false) throw new Exception("Couldn't exec smtp!");

        $result = $smtp->get_result();
		if ($result == false) throw new Exception("Couldn't get smtp result!");

        $drinks = [];

        while ($drink = $result->fetch_object("Drink")) {
            $drink->validate();
            array_push($drinks, $drink);
        }

        return $drinks;
    }

    /** Adds drinks to the database. */
    function add_drinks(array $drinks)
    {

        $smtp = $this->mysqli->prepare(
            "INSERT INTO drink (
                import_batch, number, name, manufacturer, size_in_milliliters, type, price,
                price_per_liter, origin, vintage, promille, kcal_per_hundred_ml
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"
        );

		if ($smtp == false) throw new Exception("Couldn't prepare smtp!");
		if ($this->mysqli->begin_transaction() == false) throw new Exception("Couldn't begin transaction!");
        foreach ($drinks as $drink) {
			if ($smtp->bind_param(
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
                $drink->promille,
                $drink->kcal_per_hundred_ml,
			) == false) throw new Exception("Couldn't bind_param!");
			if ($smtp->execute() == false) throw new Exception("Couldn't exec!");
        }
		if ($this->mysqli->commit() == false) throw new Exception("Couldn't commit!");
    }

    function create_import_batch(DateTime $date): int
    {
        $smtp = $this->mysqli->prepare("INSERT INTO import_batch (date) VALUES (?);");
		if ($smtp == false) throw new Exception("Couldn't create smtp!");
        $date_str = $date->format("Y-m-d H:i:s");
		if ($smtp->bind_param("s", $date_str) == false) throw new Exception("Couldn't bind param!");
		if ($smtp->execute() == false) throw new Exception("Couldn't execute!");
        $id = $this->mysqli->insert_id;

        return $id;
    }

    function set_import_batch_as_completed(int $id, bool $completed = true)
    {
        $result = $this->mysqli->query("UPDATE import_batch SET completed = $completed WHERE id = $id;");
		if ($result == false) throw new Exception("Couldn't set as complete!");
    }
}

return new DB();
