<?php
/** This file is used for generating the strings for SQL queries. */

class FilterQueryGenerator
{
    private string $filter = '';
    private string $bind_param_types = '';
    private Array $bind_param_values = [];

    private function add_to_filter_str(string $filter_str) {
        if (strlen($this->filter) != 0) $this->filter .= " AND ";
        $this->filter .= $filter_str;
    }

    public function get_db_query_data(): Array {
        return [
            "filter" => $this->filter,
            "param_types" => $this->bind_param_types,
            "param_values" => $this->bind_param_values
        ];
    }

    public function filter_by_type(string $type){
        $this->add_to_filter_str("type = ?");
        $this->bind_param_types .= "s";
        array_push($this->bind_param_values, $type);
    }

    public function filter_by_country(string $country){
        $this->add_to_filter_str("country = ?");
        $this->bind_param_types .= "s";
        array_push($this->bind_param_values, $country);
    }

    /** Filters drinks to only sizes that are at least the given value in size. */
    public function filter_by_min_size(int $min_size){
        $this->add_to_filter_str("size_in_milliliters >= ?");
        $this->bind_param_types .= "i";
        array_push($this->bind_param_values, $min_size);
    }

    /** Filters drinks to only sizes that are at most the given value in size. */
    public function filter_by_max_size(int $max_size){
        $this->add_to_filter_str("size_in_milliliters <= ?");
        $this->bind_param_types .= "i";
        array_push($this->bind_param_values, $max_size);
    }

    /** Filters drinks to only sizes that are at least the given value in price. */
    public function filter_by_min_price(int $min_price){
        $this->add_to_filter_str("price >= ?");
        $this->bind_param_types .= "i";
        array_push($this->bind_param_values, $min_price);
    }

    /** Filters drinks to only sizes that are at most the given value in price. */
    public function filter_by_max_price(int $max_price){
        $this->add_to_filter_str("price <= ?");
        $this->bind_param_types .= "i";
        array_push($this->bind_param_values, $max_price);
    }

    /** Filters drinks to only sizes that are at least the given value in kcal/100ml. */
    public function filter_by_min_energy(int $min_energy){
        $this->add_to_filter_str("kcal_per_hundred_ml >= ?");
        $this->bind_param_types .= "i";
        array_push($this->bind_param_values, $min_energy);
    }

    /** Filters drinks to only sizes that are at most the given value in kcal/100ml. */
    public function filter_by_max_energy(int $max_energy){
        $this->add_to_filter_str("kcal_per_hundred_ml <= ?");
        $this->bind_param_types .= "i";
        array_push($this->bind_param_values, $max_energy);
    }
}
