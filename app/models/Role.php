<?php 


/**
 * role model
 */
class Role
{
    /**
     * manages crud for role
     */

    // iniatiliaze db
    private $db;
    private $table;

    public function __construct()
    {
        // connect to db 
        $this->db = new MainModel();
        // associate model with $table 
        $this->table = 'roles';
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
        $exists = $this->db->getOne($this->table, 'role_name', $name);
        if($exists['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkIfNameExistEdit($id, $role_name)
    {
        $sql = "SELECT * FROM $this->table WHERE id != :id AND role_name = :role_name";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':role_name', $role_name);
        $this->db->execute();
        if($this->db->row_count()) {
            return true;
        } else {
            return false;
        }

    }





    public function getAll()
    {
        $sql = "SELECT $this->table.*,  Count(users.id) as cnt
                FROM $this->table LEFT JOIN users ON 
                $this->table.id=users.user_role_id 
                GROUP BY $this->table.id ORDER BY cnt DESC";
        return $this->db->getManySql($sql);
    }
}