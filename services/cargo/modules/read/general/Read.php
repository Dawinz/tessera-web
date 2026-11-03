<?php

class Read
{
    public $id;
    public $name;
    public $status;
    public $available_row;
    public $position;
    public $select;
    public $where;
    public $pdo;
    public $limit;
    public $order;
    public $table;
    public $code;
    public $category;
    public $rating;
    public $price;
    public $initial;
    public $point;
    public $location_id;
    public $reporting_time;
    public $departure;
    public $conductor;
    public $fullname;
    public $scanned;
    public $account;
    public $to_pay;
    public $paid;
    public $due;
    public $Route;
    public $currency;
    public $first_point;
    public $second_point;
    public $value;
  


    public $rows = []; // all database output will be saved here

    public function __construct(array $data = [])
    {
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }

        $columns = implode(", ", $this->select);

        $conditions = [];
        foreach ($this->where ?? [] as $key => $value) {
            $conditions[] = "$key = :$key";
        }

        $where = "";
        if (!empty($conditions)) {
            $where = " WHERE " . implode(" AND ", $conditions);
        }

        $sql = "SELECT $columns FROM $this->table$where $this->order $this->limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->where ?? []);

        $this->rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $this->available_row = count($this->rows);

        if ($this->available_row != 0) {
            foreach ($this->rows[0] as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }
}
