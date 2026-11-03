<?php

class ReadJson
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
    public $trip_date;
    public $bus;
    public $question;
    public $paid_remain;
    public $to_be_remain;
    public $r_no;
    public $r_name;
    public $s_no;
    public $s_name;

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
      public function toArray(): array
    {
      return [
            'success' => true,
            'message' => $this->available_row > 0 ? 'Record found' : 'No record found',
            'count' => $this->available_row,
            'data' => $this->rows
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
