<?php  



/**
 * roles controller
 */
class Roles extends MainController
{
    /**
     * manage crud operations for roles
     */

    // iniatialize models
    private $role;
    private $user;

    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");            
        }
        // load models 
        $this->role = $this->model('Role');
        $this->user = $this->model('User');
    }



    // roles home page
    public function home()
    {
    	// iniatilaize data
    	$data = [];

        // get all roles 
        $data['roles'] = $this->role->getAll();

    	// load view with data
    	$this->view('roles/index', $data);
    }




    public function create()
    {
    	// iniatilaize data
    	$data = [
    		'role_name' => '',
    		'role_name_err' => '',
    	];

    	// check method if post for storing
    	if($_SERVER['REQUEST_METHOD'] == "POST") {
    		// iniatialize data to store
    		$data_to_store = [];

    		// validate
    		$name = post_data('role_name');
    		if(!empty($name)) {
    			$data['role_name'] = $name;
    			if($this->role->checkIfNameExist($name)) {
    				$data['role_name_err'] = "Name of the role entered is already taken";
    			} else {
    				$data_to_store['role_name'] = proper_case($name);
    			}
    		} else {
    			$data['role_name_err'] = "Name of the role is required";
    		}

    		// check for validation errors 
    		if(empty($data['role_name_err'])) {
    			// store data to db and redirect to roles home
                $data_to_store['role_by'] = get_sess('logged_in_user_id');
    			$stored = $this->role->add($data_to_store);
    			if($stored) {
                    // set session
                    set_sess("message_success", "Role has been added successfully");
                    // redirect
    				redirect_to('roles');
    			} else {
    				// load view with data with errors, from db
    				$data['role_name_err'] = "Role not added to db, db error, try again";
    				$this->view('roles/create', $data);
    			}
    		} else {
    			// else load view with errors
    			$this->view('roles/create', $data);
    		}
    	} else {
    		// load view
    		$this->view('roles/create', $data);
    	}	
    }





    // role profile, provide id
    public function show($id)
    {
    	// iniatilize data
    	$data = [];

    	// fetch role with id
    	$rol = $this->role->find($id);

    	// check if null is returned
    	if($rol['count']>0) {
            // users with this roles
            $users = $this->user->user_whose_role_is($id);
            $data['users'] = $users;
    		// not null, load view with role
    		$data['role'] = $rol['data'];
    		$this->view('roles/show', $data);
    	} else {
    		// load 404 with error
    		$data['error'] = "Role queried for does not exist";
    		$this->view('errors/404', $data);
    	}
    }





    public function edit($id)
    {
        // initialize data
        $data = [
            'role_name'=> '',
            'role_name_err' => '',
            'role'=>''
        ];


        // get the role to edit
        $role = $this->role->find($id);
        // check if role fetched exists 
        if($role['count']<1) {
            // send to 404 with error
            $data['error'] = "role queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } 

        // load role name in data array
        $data['role'] = $role['data'];
        $data['role_name'] = $role['data']->role_name;
        

        // check if post method
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // iniatialize data to update
            $data_to_update = [];

            // validate and set errors
            $name = post_data('role_name');
            if(strlen($name)) {
                $data['role_name'] = $name;
                if($this->role->checkIfNameExistEdit($id, $name)) {
                    $data['role_name_err'] = "role name is already added";
                } else {
                    $data_to_update['role_name'] = proper_case($name);
                }
            } else {
                $data['role_name_err'] = "role name is required";
            }

            // check if there is any validation errors
            if(empty($data['role_name_err'])) {
                // store
                $updated = $this->role->update($id, $data_to_update);
                if($updated) {
                    // set session message and redirect to roles home
                    set_sess('message_success', "role Updated successfully");
                    redirect_to('roles/show/' . $id);
                } else {
                    // return views with errors
                    $data['role_name_err'] = "role was not updated, db error, please try again";
                    $this->view('roles/edit', $data);
                }
            } else {
                // return views with errors
                $this->view('roles/edit', $data);
            }

        } else {
            // load view if request is get
            $this->view('roles/edit', $data);
        }
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the role to delete
        $role = $this->role->find($id);
        // check if role fetched exists 
        if($role['count'] != 1) {
            // send to 404 with error
            $data['error'] = "role queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            $employees = $this->user->user_whose_role_is($id);
            $data['users'] = $employees;
            // check if request is rolt
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->role->delete($id);
                set_sess("message_info", "role deleted successfully");
                redirect_to('roles');
            } else {
                // load view to confirm delete
                $data['role'] = $role['data'];
                $this->view('roles/delete', $data);
            }
        }
    }
}