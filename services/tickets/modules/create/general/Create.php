<?php

class Create{
    public $db;
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
   
   
    

    public function __construct($pdo,$data,$table){
   
    $this->data = $data;
  

    $db_columns = implode(",", array_keys($data));
    $db_values = implode(",", $data); 
    $this->db_values = $db_values;
    $db_columns = implode(",", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));

    $this->injection = $placeholders;
    $this->db_columns = $db_columns;

    $sql = "INSERT INTO $table ($this->db_columns) VALUES ($this->injection)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($this->data);
    $this->response = "Route Added";
            }
  public function get_info(){

 echo "added";

    }
}
?>
