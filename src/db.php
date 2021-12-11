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
        assert($this->mysqli->begin_transaction());

        try {
            $dbname = getenv("DB_DATABASE");
            $result = $this->mysqli->query("CREATE DATABASE IF NOT EXISTS $dbname;");
            assert($result != false);

            $result = $this->mysqli->query(
                "CREATE TABLE IF NOT EXISTS import_batch (
                    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                    date DATETIME NOT NULL,
                    completed BOOLEAN NOT NULL DEFAULT FALSE
                );"
            );
            assert($result != false);

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
            assert($result != false);

            assert($this->mysqli->commit());
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
        assert($result != false);
        $result = $result->fetch_assoc();
        assert($result !== false);
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
        assert($smtp != false);

        $param_types = $query->get_bind_param_types();
        $param_values = $query->get_bind_param_values();
        if ($param_types != null && $param_values != null) {
            assert($smtp->bind_param($param_types, ...$param_values));
        }

        assert($smtp->execute());
        $result = $smtp->get_result();
        assert($result != false);

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

        assert($smtp != false);
        assert($this->mysqli->begin_transaction());
        foreach ($drinks as $drink) {
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
                $drink->promille,
                $drink->kcal_per_hundred_ml,
            ));
            assert($drink instanceof Drink);
            assert($smtp->execute());
        }
        $this->mysqli->commit();
    }

    function create_import_batch(DateTime $date): int
    {
        $smtp = $this->mysqli->prepare("INSERT INTO import_batch (date) VALUES (?);");
        assert($smtp != false);
        $date_str = $date->format("Y-m-d H:i:s");
        assert($smtp->bind_param("s", $date_str));

        assert($smtp->execute() != false);
        $id = $this->mysqli->insert_id;

        return $id;
    }

    function set_import_batch_as_completed(int $id, bool $completed = true)
    {
        $result = $this->mysqli->query("UPDATE import_batch SET completed = $completed WHERE id = $id;");
        assert($result !== false);
    }
}

return new DB();
