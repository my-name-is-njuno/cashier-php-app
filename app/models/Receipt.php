<?php 


/**
 * receipt model
 */
class receipt
{
    /**
     * manages crud for receipt
     */

    // iniatiliaze db
    private $db;
    private $table;

    public function __construct()
    {
        // connect to db 
        $this->db = new MainModel();
        // associate model with $table 
        $this->table = 'receipts';
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
        $exists = $this->db->getOne($this->table, 'receipt_name', $name);
        if($exists['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfNameExistEdit($id, $receipt_name)
    {
        $sql = "SELECT * FROM $this->table WHERE id != :id AND receipt_name = :receipt_name";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':receipt_name', $receipt_name);
        $this->db->execute();
        if($this->db->row_count()) {
            return true;
        } else {
            return false;
        }

    }








    public function receiptFor($id)
    {
        $sql = "SELECT * FROM $this->table WHERE receipt_customer_id = '$id'";
        return $this->db->getOneSql($sql);
    }












    public function get_total_for($menu)
    {
        $sql = "SELECT sum(receipt_paid) as paid FROM $this->table WHERE receipt_menu_id = '$menu'";
        return $this->db->getOneSql($sql);
    }







    public function get_total_for_user($id)
    {
        $sql = "SELECT sum(receipt_paid) as paid FROM $this->table WHERE receipt_by = '$id'";
        return $this->db->getOneSql($sql)['data'];
    }










    public function get_receipt_for_sigleorder($id)
    {
        $sql = "SELECT * FROM $this->table WHERE receipt_singleorder_id = '$id'";
        return $this->db->getOneSql($sql);
    }
}