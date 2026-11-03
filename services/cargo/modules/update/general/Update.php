<?php

class Update
{

    public $data;
    public $status;
    public $injection;
    public $binded_value;
    public $db_values;
    public $db_columns;
    public $pdo;
    public $response;
    public $table;
    public $position;
    public $location_id;
    public $set_columns;
    public $where;




    public function __construct($pdo, $data, $table, $where)
    {

        $this->data = $data;
        $this->table = $table;
        $this->where = $where;
  $set_parts = [];

foreach ($data as $column => $value) {
            $set_parts[] = "$column = :$column";
        }

       $this->set_columns = implode(", ", $set_parts);
        $where_parts = [];
        foreach ($where as $column => $value) {
            $where_parts[] = "$column = :where_$column";
            $data["where_$column"] = $value;
        }
        $where_sql = implode(" AND ", $where_parts);
        $sql = "UPDATE $table SET $this->set_columns WHERE $where_sql";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $this->response = "Updated Successfully";
    }
    public function get_info()
    {

        echo "added";

    }
}
?>