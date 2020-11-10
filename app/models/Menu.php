<?php 


/**
 * menu model
 */
class Menu
{
    /**
     * manages crud for menu
     */

    // iniatiliaze db
    private $db;
    private $table;

    public function __construct()
    {
        // connect to db 
        $this->db = new MainModel();
        // associate model with $table 
        $this->table = 'menus';
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

    public function checkIfItemExist($name)
    {
        $exists = $this->db->getOne($this->table, 'menu_item', $name);
        if($exists['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfItemExistEdit($id, $menu_name)
    {
        $sql = "SELECT * FROM $this->table WHERE id != :id AND menu_item = :menu_item";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':menu_item', $menu_name);
        $this->db->execute();
        if($this->db->row_count()) {
            return true;
        } else {
            return false;
        }

    }






    public function getAllPaginated($items, $page)
    {
        $sql = "SELECT $this->table.* , 
                categorys.category_name
                FROM $this->table
                INNER JOIN categorys 
                ON $this->table.menu_category_id = categorys.id";
                
        $data = $this->db->select_with_paginantion($sql, $items, $page);

        if($data) {return $data;} else {return false;}
    }





    public function getForHome()
    {
        $sql = "SELECT $this->table.* , 
                categorys.category_name
                FROM $this->table
                INNER JOIN categorys 
                ON $this->table.menu_category_id = categorys.id
                LIMIT 6";
        return $this->db->getManySql($sql);
    }






    public function getAll()
    {
        $sql = "SELECT $this->table.* , 
                categorys.category_name
                FROM $this->table
                INNER JOIN categorys 
                ON $this->table.menu_category_id = categorys.id";
        return $this->db->getManySql($sql);
    }






    public function find_with_ajax($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = '$id'";
        return $this->db->getOneSql($sql);
    }






    public function get_menu_items_for_category($id)
    {
        $sql = "SELECT $this->table.* , 
                categorys.category_name
                FROM $this->table
                INNER JOIN categorys 
                ON $this->table.menu_category_id = categorys.id
                WHERE $this->table.menu_category_id = '$id'";
        return $this->db->getManySql($sql);
    }
}