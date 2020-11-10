<?php 


/**
 * singleorder model
 */
class singleorder
{
    /**
     * manages crud for singleorder
     */

    // iniatiliaze db
    private $db;
    private $table;

    public function __construct()
    {
        // connect to db 
        $this->db = new MainModel();
        // associate model with $table 
        $this->table = 'singleorders';
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






    public function getAllPaginated($items, $page)
    {
        $sql = "SELECT $this->table.*, 
                customers.customer_name, customers.id as cid,
                receipts.receipt_paid, receipts.receipt_balance, receipts.id as rid  
                FROM $this->table 
                INNER JOIN customers ON customers.id = $this->table.singleorder_customer_id 
                INNER JOIN receipts ON receipts.receipt_singleorder_id = $this->table.id";

        $data = $this->db->select_with_paginantion($sql, $items, $page);

        if($data) {return $data;} else {return false;}
    }









    public function getForThisMonth()
    {
        $sql = "SELECT $this->table.*, 
                customers.customer_name, customers.id as cid,
                receipts.receipt_paid, receipts.receipt_balance, receipts.id as rid  
                FROM $this->table 
                INNER JOIN customers ON customers.id = $this->table.singleorder_customer_id 
                INNER JOIN receipts ON receipts.receipt_singleorder_id = $this->table.id
                WHERE MONTH($this->table.singleorder_at) = MONTH(CURRENT_DATE())
                AND YEAR($this->table.singleorder_at) = YEAR(CURRENT_DATE())";
        return $this->db->getManySql($sql);
        
    }






    public function getOrdersBetween($from, $to)
    {
        $sql = "SELECT $this->table.*, 
                customers.customer_name, customers.id as cid,
                receipts.receipt_paid, receipts.receipt_balance, receipts.id as rid  
                FROM $this->table 
                INNER JOIN customers ON customers.id = $this->table.singleorder_customer_id 
                INNER JOIN receipts ON receipts.receipt_singleorder_id = $this->table.id
                WHERE $this->table.singleorder_at BETWEEN '$from' AND '$to' ";
        return $this->db->getManySql($sql);
    }









    public function getDailyReport($from, $to)
    {   
        $to = date('Y-m-d',strtotime($to . "+1 days"));
        $from = $tomorrow = date('Y-m-d',strtotime($from));
        
        $sql = "SELECT $this->table.*, 
                customers.customer_name, customers.customer_contacts, customers.id as cid,
                receipts.receipt_paid, receipts.receipt_balance, receipts.id as rid  
                FROM $this->table 
                INNER JOIN customers ON customers.id = $this->table.singleorder_customer_id 
                INNER JOIN receipts ON receipts.receipt_singleorder_id = $this->table.id
                WHERE $this->table.singleorder_at BETWEEN '$from' AND '$to' ";
        return $this->db->getManySql($sql);
    }







    public function getTodaysReport()
    {
        $sql = "SELECT $this->table.*, 
                customers.customer_name, customers.customer_contacts, customers.id as cid,
                receipts.receipt_paid, receipts.receipt_balance, receipts.id as rid  
                FROM $this->table 
                INNER JOIN customers ON customers.id = $this->table.singleorder_customer_id 
                INNER JOIN receipts ON receipts.receipt_singleorder_id = $this->table.id
                WHERE DATE(singleorder_at) = CURDATE() ";
        return $this->db->getManySql($sql);
    }






    public function get_users_orders($id)
    {
        $sql = "SELECT $this->table.*, 
                customers.customer_name, customers.id as cid,
                receipts.receipt_paid, receipts.receipt_balance, receipts.id as rid  
                FROM $this->table 
                INNER JOIN customers ON customers.id = $this->table.singleorder_customer_id 
                INNER JOIN receipts ON receipts.receipt_singleorder_id = $this->table.id
                WHERE $this->table.singleorder_by = '$id' ";
        return $this->db->getManySql($sql);
    }




    public function getMonthlyReport($month, $year)
    {
        $sql = "SELECT count('id') as sales, sum(singleorder_total) as ttl, date(singleorder_at) as at 
                FROM $this->table 
                
                WHERE MONTH(singleorder_at) = '$month' AND YEAR(singleorder_at) = '$year'
                group by date(singleorder_at) ";
        return $this->db->getManySql($sql);
    }

    

    
}