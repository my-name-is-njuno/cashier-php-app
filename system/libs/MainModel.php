<?php 



/**
 * main model
 */
class MainModel
{

    	private $host = DB_HOST;
	    private $user = DB_USER;
	    private $pass = DB_PASS;
	    private $dbname = DB_NAME;


	    private $dbh;

	    private $stmt;

	    private $error;


	    public function __construct()
	    {
	        
	        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;

	        $options = array(
	        	PDO::ATTR_PERSISTENT => true,
	        	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	        );

	        try {
	        	$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);	        	
	        } catch (PDOException $e) {
	        	$this->error = $e->getMessage();
	        	echo $this->error;
	        }
	    }




	    public function query($sql)
	    {
	    	$this->stmt = $this->dbh->prepare($sql);
	    	return $this->stmt;
	    }






	    public function bind($param, $value, $type=null)
	    {
	    	if(is_null($type)) {
	    		switch (true) {
	    			case is_int($value):
	    				$type = PDO::PARAM_INT;
	    				break;
	    			case is_bool($value):
	    				$type = PDO::PARAM_BOOL;
	    				break;
	    			case is_null($value):
	    				$type = PDO::PARAM_NULL;
	    				break;
	    			default:
	    				$type = PDO::PARAM_STR;
	    				break;
	    		}
	    	}

	    	$this->stmt->bindValue($param, $value, $type);

	    }





	    public function execute()
	    {
	    	return $this->stmt->execute();
	    }


	    public function fetch_many()
	    {
	    	$this->execute();
	    	return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	    }




	    public function fetch_first()
	    {
	    	$this->execute();
	    	return $this->stmt->fetch(PDO::FETCH_OBJ);

	    }



	    public function row_count()
	    {
	    	return $this->stmt->rowCount();
	    }



	    public function last_inserted_id()
	    {
	    	return $this->dbh->lastInsertId();
	    }




	    public function get($table, $id)
	    {
	    	$sql = "SELECT * FROM $table WHERE id = :id";
	    	$this->query($sql);
	    	$this->bind(':id', $id);
	    	if($this->execute()) {
	    		return ['data'=>$this->fetch_first(), 'count'=>$this->row_count()];
	    	} else {
	    		return false;
	    	}
	    	
	    }


	    public function getAll($table)
	    {
	    	$this->query("SELECT * FROM $table");
	    	return $this->fetch_many();
	    }


	    public function getOne($table, $column, $value)
    	{
    		$sql = "SELECT * FROM $table WHERE $column = :value";
	    	$this->query($sql);
	    	$this->bind(':value', $value);
	    	if($this->execute()) {
	    		return ['data'=>$this->fetch_first(), 'count'=>$this->row_count()];
	    	} else {
	    		return false;
	    	}
	    	
    	}



    	public function getOneSql($sql)
    	{
	    	$this->query($sql);
	    	if($this->execute()) {
	    		return ['data'=>$this->fetch_first(), 'count'=>$this->row_count()];
	    	} else {
	    		return false;
	    	}
	    	
    	}


    	public function getManySql($sql)
    	{
	    	$this->query($sql);
	    	if($this->execute()) {
	    		return ['data'=>$this->fetch_many(), 'count'=>$this->row_count()];
	    	} else {
	    		return false;
	    	}
	    	
    	}


    	public function getMany($table, $column, $value)
    	{
    		$sql = "SELECT * FROM $table WHERE $column = :value";
	    	$this->query($sql);
	    	$this->bind(':value', $value);
	    	if($this->execute()) {
	    		return ['data'=>$this->fetch_many(), 'count'=>$this->row_count()];
	    	} else {
	    		return false;
	    	}
	    	
    	}


    	public function getLike($table, $data)
    	{
    		$binding_values = ' WHERE ';
    		foreach ($data as $k=>$v) {
    			$binding_values .= " $k LIKE :$v,";
    		}
    		$binding_values  = rtrim($binding_values, ',');
    		$sql = "SELECT * FROM $table $binding_values";
    		$this->query($binding_values);
    		foreach ($data as $k->$v) {
    			$this->bind(":$k", "%$v%");
    		}

    		if($this->execute()) {
	    		return ['data'=>$this->fetch_many(), 'count'=>$this->row_count()];
	    	}
	    	
    	}


	    public function insert($table, $data)
	    {
	    	$fields = implode(", " ,array_keys($data));
	    	$binding_values = ':'.implode(', :', array_keys($data));
	    	$sql = "INSERT INTO $table ($fields) VALUES ($binding_values)";
	    	$this->query($sql);
	    	foreach ($data as $k => $v) {
	    		$this->bind(":$k", $v);
	    	}
	    	if($this->execute()) {
	    		return $this->last_inserted_id();
	    	} else {
	    		return false;
	    	}
	    }



	   public function insertBulk($table, $data)
		{
			$this->dbh->beginTransaction();
			$field_names = implode(",", array_keys($data[0]));
			$field_names_binding_params = ":" . implode(", :", array_keys($data[0]));

			$field_names_binding_params_for_select = "";
			foreach (array_keys($data[0]) as $key) {
			$field_names_binding_params_for_select .= " $key = :$key AND";
			}


			$field_names_binding_params_for_select= rtrim($field_names_binding_params_for_select, " AND");


			

			// Insert each record
			foreach ($data as $insertRow) {

				// Prepare statement
				$this->stmt = $this->dbh->prepare("INSERT INTO $table ($field_names) SELECT $field_names_binding_params  WHERE NOT EXISTS (SELECT * FROM `$table` WHERE $field_names_binding_params_for_select LIMIT 1)");
				

				// now loop through each inner array to match binded values
				foreach ($insertRow as $column => $value) {
					$this->stmt->bindValue(":{$column}", $value);
				}

				// Execute statement to add to transaction
				try {
					$this->stmt->execute();
				} catch (PDOException $th) {
					die($th->getMessage());
				}
				// Clear statement for next record (not necessary, but good practice)
				$this->stmt = null;
			}

			// Commit all inserts
			try {
				if ($this->dbh->commit()) {
					return true;
				} else {
					return false;
				}
			} catch (PDOException $th) {
				die($th);
			}
			
		}






	    public function update($table, $id, $data)
	    {
	    	$fields_values = " SET ";
	    	foreach ($data as $k=>$v) {
	    		$fields_values .= "$k = :$k,";
	    	}
	    	$fields_values = rtrim($fields_values, ",");
	    	$sql = "UPDATE $table $fields_values WHERE id = :id ";
	    	$this->query($sql);
	    	$this->bind(':id', $id);
	    	foreach ($data as $k => $v) {
	    		$this->bind(":$k", $v);
	    	}
	    	if($this->execute()) {
	    		return true;
	    	} else {
	    		return false;
	    	}
	    }


	    public function delete($table, $id)
	    {
	    	$sql = "DELETE FROM $table WHERE id = :id";
	    	$this->query($sql);
	    	$this->bind(':id',$id);
	    	if($this->execute()) {
	    		return true;
	    	} else {
	    		return false;
	    	}
    	}












    	public function select_with_paginantion($sql, $limit, $page=1)
		{
			$this->query($sql);
			$this->execute();
			$total_results = $this->row_count();
			$total_pages = ceil($total_results / $limit);
			// $page = 1;
			$start = ($page - 1) * $limit;
			$sql_for_pag = $sql . " LIMIT $start, $limit";
			$this->query($sql_for_pag);
			
			if($this->execute()) {
				$data = $this->fetch_many();
				return [$total_pages, $data, $total_results];
			} else {
				return false;
			}
			
		}










		public function select($table, $where_conditions = array(), $like_conditions = array(), $columns = array(), $sort = array(), $limit = 0)
		{	
			$sql = "";
			if(sizeof($columns) < 1 )  {
				$sql = "SELECT * FROM `$table` ";
			}
			if(sizeof($columns)  > 1 ) {
				$columns_to_select = implode(',', $columns);
				$columns_to_select = rtrim($columns_to_select,",");
				$sql = "SELECT $columns_to_select FROM `$table` ";
			}
			if(sizeof($where_conditions) > 0) {
				$conditions_keys = NULL;
				foreach ($where_conditions as $key => $value) {
					$conditions_keys .= " `$key` = :$key && ";
				}
				// echo $conditions_keys;
				$conditions_keys = rtrim($conditions_keys, ' &&');
				// echo $conditions_keys;
				$sql .= " WHERE $conditions_keys ";
			}

			if(sizeof($like_conditions) > 0) {
				$conditions_keys = NULL;
				foreach ($like_conditions as $key => $value) {
					$conditions_keys .= " `$key` LIKE :$key && ";
				}
				// echo $conditions_keys;
				$conditions_keys = rtrim($conditions_keys, ' &&');
				// echo $conditions_keys;
				$sql .= " WHERE $conditions_keys ";
			}
			
			if(sizeof($sort) > 0) {
				$sorting_keys = NULL;
				foreach ($sort as $key => $value) {
					$sorting_keys .= " $key = $value ";
				}
				$sql .= " ORDER BY $sorting_keys ";
			}
			if ($limit > 0 ) {
				$sql .= " LIMIT $limit ";
			}
			
			$this->stmt = $this->dbh->prepare($sql);
			if(sizeof($where_conditions) > 0) {
				try {
					$this->stmt->execute($where_conditions);
				} catch (PDOException $th) {
					die($th->getMessage());
				}		
			} elseif(sizeof($like_conditions) > 0) {
				$like_conditions_execute = [];
				foreach ($like_conditions as $key => $value) {
					$like_conditions_execute[$key] = "%$value%";
				}

				try {
					$this->stmt->execute($like_conditions_execute);
				} catch (PDOException $th) {
					die($th->getMessage());
				}
			} else {
				try {
					$this->stmt->execute();
				} catch (PDOException $th) {
					die($th->getMessage());
				}
			}


			

		}



    	


    	
}