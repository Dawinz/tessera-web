<?php
require_once __DIR__ . '/Read.php';
class AssignedRoutes
{
    public $pdo;
    public $results = [];
    public $username;

    public function __construct($pdo, $username)
    {
        // The "$this" keyword links the parameters to the class properties
        $this->pdo = $pdo;
        $this->username = $username;

        $select = ['route_full_name'];
        $where = ["username" => $username];
        $assign_routes = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "assigned_routes", 'order' => "", 'limit' => ""]);
        $this->results = $assign_routes->rows;
    }
    public function get_details(): array
    {
        return $this->results;
    }
}
class UserName
{
    public $pdo;
    public $results = [];
    public $username;

    public function __construct($pdo)
    {
        // The "$this" keyword links the parameters to the class properties
        $this->pdo = $pdo;
        

        $select = ['username','firstname','surname'];
        $where = [];
        $assign_routes = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "users", 'order' => "", 'limit' => ""]);
        $this->results = $assign_routes->rows;
    }
    public function get_details(): array
    {
        return $this->results;
    }
}
