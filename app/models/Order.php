<?php 


/**
 * order model
 */
class Order
{
    /**
     * manages crud for order
     */

    // iniatiliaze db
    private $db;
    private $table;

    public function __construct()
    {
        // connect to db 
        $this->db = new MainModel();
        // associate model with $table 
        $this->table = 'orders';
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
        $exists = $this->db->getOne($this->table, 'order_name', $name);
        if($exists['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfNameExistEdit($id, $order_name)
    {
        $sql = "SELECT * FROM $this->table WHERE id != :id AND order_name = :order_name";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':order_name', $order_name);
        $this->db->execute();
        if($this->db->row_count()) {
            return true;
        } else {
            return false;
        }

    }




    public function orderFor($id)
    {
        $sql = "SELECT * FROM $this->table WHERE order_customer_id = '$id'";
        return $this->db->getOneSql($sql);
    }






    public function get_for_item($menu)
    {
        $sql = "SELECT sum(order_quantity) as orders, sum(order_total) as totals FROM $this->table WHERE order_menu_id = '$menu'";
        return $this->db->getOneSql($sql);
    }






    public function get_orders_for_sigleorder($id)
    {
        $sql = "SELECT $this->table.* ,
            menus.menu_item, menus.menu_price, menus.id as mid 
            FROM $this->table 
            INNER JOIN menus ON menus.id = $this->table.order_menu_id
            WHERE order_singleorder_id = '$id'";
        return $this->db->getManySql($sql);
    }








    public function checkIfOrderExists($id, $menu_id)
    {
        $sql = "SELECT id
            FROM $this->table 
            WHERE order_singleorder_id = '$id' AND order_menu_id = '$menu_id'";

        $exists = $this->db->getOneSql($sql);

        if($exists) {
            if($exists['count']) {
                return $exists['data']->id;
            }
        }
        return false;
    }










    public function checkIfAlreadyOrdered($menu_id, $singleorder_id)
    {
        $sql = "SELECT *
            FROM $this->table 
            WHERE order_singleorder_id = '$singleorder_id' AND order_menu_id = '$menu_id'";
        $exists = $this->db->getOneSql($sql);

        if($exists) {
            if($exists['count']) {
                return $exists['data'];
            }
        }
        return false;

    }
















    public function get_orders_for($id)
    {
        $sql = "SELECT $this->table.*, customers.customer_name FROM $this->table INNER JOIN customers ON $this->table.order_customer_id = customers.id WHERE order_menu_id = '$id' ORDER BY id DESC LIMIT 10";
        return $this->db->getManySql($sql);
    }




    
}