<?php


class user
{
    private $db;
    public function __construct(){

        $dsn = "mysql:host=localhost;dbname=revision";

        // connect
        $this->db = new PDO($dsn, 'root', '');

    }

    public function getAllData(){
        $data = $this->db->query("SELECT * FROM employee")->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function update($tableName, $values, $primary_key)
    {
        $keys  = array_keys($values);
        $count = count($keys);

        // prepare the statement of attributes
        $statement = '';
        for ($i = 0; $i < $count; $i++)
        {
            $statement .= "`{$keys[$i]}` = " . "'{$values[$keys[$i]]}'";

            if ($i !== $count - 1)
                $statement .= ', ';
        }

        // extract the primary key name
        $primaryKey = key($primary_key);

        // condition of the edit
        $condition = "$primaryKey = $primary_key[$primaryKey]";

        // execute the query
        $query = "UPDATE $tableName SET $statement WHERE $condition";
        $this->db->query($query);
    }

    public function delete($id){
        $this->db->query("DELETE FROM employee WHERE Employee_id = {$id}")
            ->execute();
    }

    public function add($tableName, $values){

        $keys = array_keys($values);
        $count = count($keys);

        // prepare the statement of attributes
        $attributes = "(";
        for ($i = 0; $i < $count; $i++)
        {
            $attributes .= "`$keys[$i]`";
            if ($i !== $count - 1)
                $attributes .= ', ';
        }
        $attributes .= ')';

        // prepare the statement of the values
        $data = "(";
        for ($i = 0; $i < $count; $i++)
        {
            $data .= "'{$values[$keys[$i]]}'";
            if ($i !== $count - 1)
                $data .= ', ';
        }
        $data .= ')';

        // execute the query
        $query = "INSERT INTO $tableName $attributes VALUES $data";
        $this->db->query($query);

    }
}