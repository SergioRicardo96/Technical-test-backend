<?php

namespace App\Libs;

use DateTime;
use Exception;
use mysqli;

class Model
{
    protected $dbHost;
    protected $dbUserName;
    protected $dbPassword;
    protected $dbDatabase;
    protected $dbPort;
    protected $charset;

    protected $connection;
    protected $query;
    protected $table;
    protected $hidden = [];

    public function __construct()
    {
        $this->dbHost = Config::get('database', 'connections.mysql.host');
        $this->dbUserName = Config::get('database', 'connections.mysql.username');
        $this->dbPassword = Config::get('database', 'connections.mysql.password');
        $this->dbDatabase = Config::get('database', 'connections.mysql.database');
        $this->dbPort = Config::get('database', 'connections.mysql.port');
        $this->charset = Config::get('database', 'connections.mysql.charset');

        $this->connection();
    }

    public function connection()
    {
        $this->connection =  new mysqli($this->dbHost, $this->dbUserName, $this->dbPassword, $this->dbDatabase, $this->dbPort);

        if($this->connection->connect_error){
            die('Connection error' . $this->connection->connect_error);
        }

        $this->connection->set_charset($this->charset);
    }

    public function query($sql, $data = [], $params = null)
    {
        if($data){
            if(is_null($params)){
                $params = str_repeat('s', count($data));
            }

            $stmt = $this->connection->prepare($sql);
            if ($stmt === false) {
                die('Prepare failed: ' . $this->connection->error);
            }
            $stmt->bind_param($params, ...$data);
            $stmt->execute();

            $this->query = $stmt->get_result();
        }else{
            $this->query = $this->connection->query($sql);
        }

        return $this;
    }

    public function first()
    {
        return $this->query->fetch_assoc();
    }

    public function get()
    {
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->get();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        return $this->query($sql, [$id], 'i')->first();
    }

    public function validateExists($table, $column, $value)
    {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
        $result = $this->query($sql, [$value])->first();
        return $result['COUNT(*)'] > 0;
    }

    public function where($column, $operator, $value = null)
    {
        if(is_null($value)){
            $value = $operator;
            $operator = '=';
        }

        $value = $this->connection->real_escape_string($value);

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";
        $this->query($sql, [$value]);

        return $this;
    }

    public function create($data)
    {
        $date = date('Y-m-d H:i:s');
        $data['created_at'] = $date;
        $data['updated_at'] = $date;

        $columns = array_keys($data);
        $columns = implode(', ', $columns);

        $values = array_values($data);
        $placeholders = rtrim(str_repeat('?, ', count($values)), ', ');

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, $values);

        $insertId = $this->connection->insert_id;
        return $this->find($insertId);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

        $fields = [];

        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
        }

        $fields = implode(', ', $fields);

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";
        $values = array_values($data);
        $values[] = $id;
        
        $this->query($sql, $values);

        return $this->find($id);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $this->query($sql, [$id], 'i');
    }
}