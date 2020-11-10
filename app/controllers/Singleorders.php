<?php  



/**
 * singleorders controller
 */
class Singleorders extends MainController
{
    /**
     * manage crud operations for singleorders
     */

    // iniatialize models
    private $singleorder;

    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");            
        }
        // load models 
        $this->singleorder = $this->model('Singleorder');
    }



    // singleorders home page
    public function home()
    {
    	// iniatilaize data
    	$data = [];

        // get all singleorders 
        $data['singleorders'] = $this->singleorder->all();

    	// load view with data
    	$this->view('singleorders/index', $data);
    }




    public function create()
    {
    	// iniatilaize data
    	$data = [
    		'singleorder_name' => '',
    		'singleorder_name_err' => '',
    	];

    	// check method if post for storing
    	if($_SERVER['REQUEST_METHOD'] == "POST") {
    		// iniatialize data to store
    		$data_to_store = [];

    		// validate
    		$name = post_data('singleorder_name');
    		if(!empty($name)) {
    			$data['singleorder_name'] = $name;
    			if($this->singleorder->checkIfNameExist($name)) {
    				$data['singleorder_name_err'] = "Name of the singleorder entered is already taken";
    			} else {
    				$data_to_store['singleorder_name'] = proper_case($name);
    			}
    		} else {
    			$data['singleorder_name_err'] = "Name of the singleorder is required";
    		}

    		// check for validation errors 
    		if(empty($data['singleorder_name_err'])) {
    			// store data to db and redirect to singleorders home
                $data_to_store['singleorder_created_by'] = get_sess('logged_in_user_id');
    			$stored = $this->singleorder->add($data_to_store);
    			if($stored) {
                    // set session
                    set_sess("message_success", "singleorder has been added successfully");
                    // redirect
    				redirect_to('singleorders/show/'.$stored);
    			} else {
    				// load view with data with errors, from db
    				$data['singleorder_name_err'] = "singleorder not added to db, db error, try again";
    				$this->view('singleorders/create', $data);
    			}
    		} else {
    			// else load view with errors
    			$this->view('singleorders/create', $data);
    		}
    	} else {
    		// load view
    		$this->view('singleorders/create', $data);
    	}	
    }





    // singleorder profile, provide id
    public function show($id)
    {
    	// iniatilize data
    	$data = [];

    	// fetch singleorder with id
    	$rol = $this->singleorder->find($id);

    	// check if null is returned
    	if($rol['count']>0) {
    		// not null, load view with singleorder
    		$data['singleorder'] = $rol['data'];
    		$this->view('singleorders/show', $data);
    	} else {
    		// load 404 with error
    		$data['error'] = "singleorder queried for does not exist";
    		$this->view('errors/404', $data);
    	}
    }





    public function edit($id)
    {
        // initialize data
        $data = [
            'singleorder_name'=> '',
            'singleorder_name_err' => '',
            'singleorder'=>''
        ];


        // get the singleorder to edit
        $singleorder = $this->singleorder->find($id);
        // check if singleorder fetched exists 
        if($singleorder['count']<1) {
            // send to 404 with error
            $data['error'] = "singleorder queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } 

        // load singleorder name in data array
        $data['singleorder'] = $singleorder['data'];
        $data['singleorder_name'] = $singleorder['data']->singleorder_name;
        

        // check if post method
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // iniatialize data to update
            $data_to_update = [];

            // validate and set errors
            $name = post_data('singleorder_name');
            if(strlen($name)) {
                $data['singleorder_name'] = $name;
                if($this->singleorder->checkIfNameExistEdit($id, $name)) {
                    $data['singleorder_name_err'] = "singleorder name is already added";
                } else {
                    $data_to_update['singleorder_name'] = proper_case($name);
                }
            } else {
                $data['singleorder_name_err'] = "singleorder name is required";
            }

            // check if there is any validation errors
            if(empty($data['singleorder_name_err'])) {
                // store
                $updated = $this->singleorder->update($id, $data_to_update);
                if($updated) {
                    // set session message and redirect to singleorders home
                    set_sess('message_success', "singleorder Updated successfully");
                    redirect_to('singleorders/show/' . $id);
                } else {
                    // return views with errors
                    $data['singleorder_name_err'] = "singleorder was not updated, db error, please try again";
                    $this->view('singleorders/edit', $data);
                }
            } else {
                // return views with errors
                $this->view('singleorders/edit', $data);
            }

        } else {
            // load view if request is get
            $this->view('singleorders/edit', $data);
        }
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the singleorder to delete
        $rol = $this->singleorder->find($id);
        // check if singleorder fetched exists 
        if($rol['count'] != 1) {
            // send to 404 with error
            $data['error'] = "singleorder queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            // check if request is rolt
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->singleorder->delete($id);
                set_sess("message_info", "singleorder deleted successfully");
                redirect_to('singleorders');
            } else {
                // load view to confirm delete
                $data['rol'] = $rol['data'];
                $this->view('singleorders/delete', $data);
            }
        }
    }
}