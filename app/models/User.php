<?php



/**
 * user
 */
class User
{

	private $db;
    private $table;

    public function __construct()
    {
        // set table and connect to db
        $this->db = new MainModel();
        $this->table = 'users';
    }

    // get all users
    public function all()
    {
        return $this->db->getAll($this->table);
    }

    public function all_for_show()
    {
        $sql = "SELECT users.*, roles.role_name FROM users inner join roles on users.user_role_id = roles.id ";
        return $this->db->getManySql($sql);
    }


    // insert a new user
    public function add($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // update an existing record in db
    public function update($id, $data)
    {
        return $this->db->update($this->table, $id, $data);
    }

    // find a user with id
    public function find($id)
    {
        return $this->db->get($this->table, $id);
    }


    // find a user with id
    public function find_for_show($id)
    {
        $sql = "SELECT users.user_name, users.user_email, roles.role_name FROM $this->table INNER JOIN roles ON users.user_role_id = roles.id  WHERE users.id = '$id'";
        return $this->db->getOneSql($sql);
    }



    public function get_user_blogs($id)
    {
        $sql = "SELECT posts.*, categorys.category_name FROM posts INNER JOIN categorys ON posts.post_category_id = categorys.id  WHERE posts.post_user_id = '$id'";
        return $this->db->getManySql($sql);
    }


    // delete a user
    public function delete($id)
    {
        return $this->db->delete($this->table, $id);
    }



    public function checkIfEmailExist($email)
    {
        $exists = $this->db->getOne($this->table, 'user_email', $email);
        if($exists['count'] > 0) {
            return $exists['data'];
        } else {
            return false;
        }
    }


    public function checkIfNameExist($name)
    {
        $exists = $this->db->getOne($this->table, 'user_name', $name);
        if($exists['count'] > 0) {
            return $exists['data'];
        } else {
            return false;
        }
    }

    public function checkIfEmailExistEdit($id, $user_email)
    {
        $sql = "SELECT * FROM $this->table WHERE id != :id AND user_email = :user_email";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':user_email', $user_email);
        $this->db->execute();
        if($this->db->row_count()) {
            return true;
        } else {
            return false;
        }

    }




    public function checkIfNameExistEdit($id, $user_name)
    {
        $sql = "SELECT * FROM $this->table WHERE id != :id AND user_name = :user_name";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':user_name', $user_name);
        $this->db->execute();
        if($this->db->row_count()) {
            return true;
        } else {
            return false;
        }

    }

















    // get user for show
    public function get_user_for_show($id)
    {
        $sql = "SELECT * FROM $this->table
               WHERE $this->table.id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        if($this->db->execute()) {
            return $this->db->fetch_first();
        } else {
            return false;
        }
    }




    // check if user has a certain role
    public function getUsersRoles($id)
    {
       $sql = "SELECT $this->table.*, users_roles.user_id, users_roles.role_id, roles.role_name
               FROM users_roles
               INNER JOIN $this->table ON $this->table.id = users_roles.user_id
               INNER JOIN roles ON users_roles.role_id = roles.id
               WHERE users_roles.user_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        if($this->db->execute()) {
            return ['data' => $this->db->fetch_many(), 'count' =>$this->db->row_count()];
        } else {
            return false;
        }
    }



    // return users with certain role
    public function users_who_can($role)
    {
        $sql = "SELECT $this->table.*, users_roles.user_id, users_roles.role_id, roles.role_name
               FROM $this->table.
               INNER JOIN users_roles ON $this->table.id = users_roles.user_id
               INNER JOIN roles ON users_roles.role_id = roles.id
               WHERE roles.id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        if($this->db->execute()) {
            return ['data' => $this->db->fetch_many(), 'count' =>$this->db->row_count()];
        } else {
            return false;
        }
    }






    public function userCan($user, $role)
    {
        $all_roles = $this->getUsersRoles($user);
        $roles = [];
        if($all_roles) {
            foreach ($all_roles['data'] as $value) {
                $roles[] = $value->role_name;
            }
        }
        if (in_array($role, $roles)) {
            return true;
        } else {
            return false;
        }
    }









    public function getAll()
    {
        $sql = "SELECT $this->table.*, roles.role_name, roles.id as rid, Count(activitys.id) as cnt
                FROM $this->table
                LEFT JOIN activitys ON $this->table.id=activitys.activity_user_id
                INNER JOIN roles ON users.user_role_id = roles.id
                GROUP BY $this->table.id ORDER BY cnt DESC";
        return $this->db->getManySql($sql);
    }






    public function user_whose_role_is($id)
    {
        $sql = "SELECT $this->table.*, roles.role_name, roles.id as rid, Count(activitys.id) as cnt
                FROM $this->table
                LEFT JOIN activitys ON $this->table.id=activitys.activity_user_id
                INNER JOIN roles ON users.user_role_id = roles.id
                WHERE user_role_id = '$id'
                GROUP BY $this->table.id ORDER BY cnt DESC";
        return $this->db->getManySql($sql);
    }



}
