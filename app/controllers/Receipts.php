<?php  



/**
 * receipts controller
 */
class Receipts extends MainController
{
    /**
     * manage crud operations for receipts
     */

    // iniatialize models
    private $receipt;

    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");            
        }
        // load models 
        $this->receipt = $this->model('Receipt');
    }



    // receipts home page
    public function home()
    {
    	// iniatilaize data
    	$data = [];

        // get all receipts 
        $data['receipts'] = $this->receipt->all();

    	// load view with data
    	$this->view('receipts/index', $data);
    }




    public function create()
    {
    	// iniatilaize data
    	$data = [
    		'receipt_name' => '',
    		'receipt_name_err' => '',
    	];

    	// check method if post for storing
    	if($_SERVER['REQUEST_METHOD'] == "POST") {
    		// iniatialize data to store
    		$data_to_store = [];

    		// validate
    		$name = post_data('receipt_name');
    		if(!empty($name)) {
    			$data['receipt_name'] = $name;
    			if($this->receipt->checkIfNameExist($name)) {
    				$data['receipt_name_err'] = "Name of the receipt entered is already taken";
    			} else {
    				$data_to_store['receipt_name'] = proper_case($name);
    			}
    		} else {
    			$data['receipt_name_err'] = "Name of the receipt is required";
    		}

    		// check for validation errors 
    		if(empty($data['receipt_name_err'])) {
    			// store data to db and redirect to receipts home
                $data_to_store['receipt_created_by'] = get_sess('logged_in_user_id');
    			$stored = $this->receipt->add($data_to_store);
    			if($stored) {
                    // set session
                    set_sess("message_success", "receipt has been added successfully");
                    // redirect
    				redirect_to('receipts/show/'.$stored);
    			} else {
    				// load view with data with errors, from db
    				$data['receipt_name_err'] = "receipt not added to db, db error, try again";
    				$this->view('receipts/create', $data);
    			}
    		} else {
    			// else load view with errors
    			$this->view('receipts/create', $data);
    		}
    	} else {
    		// load view
    		$this->view('receipts/create', $data);
    	}	
    }





    // receipt profile, provide id
    public function show($id)
    {
    	// iniatilize data
    	$data = [];

    	// fetch receipt with id
    	$rol = $this->receipt->find($id);

    	// check if null is returned
    	if($rol['count']>0) {
    		// not null, load view with receipt
    		$data['receipt'] = $rol['data'];
    		$this->view('receipts/show', $data);
    	} else {
    		// load 404 with error
    		$data['error'] = "receipt queried for does not exist";
    		$this->view('errors/404', $data);
    	}
    }





    public function edit($id)
    {
        // initialize data
        $data = [
            'receipt_name'=> '',
            'receipt_name_err' => '',
            'receipt'=>''
        ];


        // get the receipt to edit
        $receipt = $this->receipt->find($id);
        // check if receipt fetched exists 
        if($receipt['count']<1) {
            // send to 404 with error
            $data['error'] = "receipt queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } 

        // load receipt name in data array
        $data['receipt'] = $receipt['data'];
        $data['receipt_name'] = $receipt['data']->receipt_name;
        

        // check if post method
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // iniatialize data to update
            $data_to_update = [];

            // validate and set errors
            $name = post_data('receipt_name');
            if(strlen($name)) {
                $data['receipt_name'] = $name;
                if($this->receipt->checkIfNameExistEdit($id, $name)) {
                    $data['receipt_name_err'] = "receipt name is already added";
                } else {
                    $data_to_update['receipt_name'] = proper_case($name);
                }
            } else {
                $data['receipt_name_err'] = "receipt name is required";
            }

            // check if there is any validation errors
            if(empty($data['receipt_name_err'])) {
                // store
                $updated = $this->receipt->update($id, $data_to_update);
                if($updated) {
                    // set session message and redirect to receipts home
                    set_sess('message_success', "receipt Updated successfully");
                    redirect_to('receipts/show/' . $id);
                } else {
                    // return views with errors
                    $data['receipt_name_err'] = "receipt was not updated, db error, please try again";
                    $this->view('receipts/edit', $data);
                }
            } else {
                // return views with errors
                $this->view('receipts/edit', $data);
            }

        } else {
            // load view if request is get
            $this->view('receipts/edit', $data);
        }
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the receipt to delete
        $rol = $this->receipt->find($id);
        // check if receipt fetched exists 
        if($rol['count'] != 1) {
            // send to 404 with error
            $data['error'] = "receipt queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            // check if request is rolt
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->receipt->delete($id);
                set_sess("message_info", "receipt deleted successfully");
                redirect_to('receipts');
            } else {
                // load view to confirm delete
                $data['rol'] = $rol['data'];
                $this->view('receipts/delete', $data);
            }
        }
    }
}