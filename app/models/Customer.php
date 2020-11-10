<?php 


/**
 * customer model
 */
class Customer
{
    /**
     * manages crud for customer
     */

    // iniatiliaze db
    private $db;
    private $table;

    public function __construct()
    {
        // connect to db 
        $this->db = new MainModel();
        // associate model with $table 
        $this->table = 'customers';
    }


    public function all()
    {
        return $this->db->getAll($this->table);
    }

    public function add($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->update($this->table, $id, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, $id);
    }

    public function find($id)
    {
        return $this->db->get($this->table, $id);
    }

    public function checkIfNameExist($name)
    {
        $exists = $this->db->getOne($this->table, 'customer_name', $name);
        if($exists['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfNameExistEdit($id, $customer_name)
    {
        $sql = "SELECT * FROM $this->table WHERE id != :id AND customer_name = :customer_name";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':customer_name', $customer_name);
        $this->db->execute();
        if($this->db->row_count()) {
            return true;
        } else {
            return false;
        }

    }
}