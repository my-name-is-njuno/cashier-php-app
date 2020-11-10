<?php  



/**
 * orders controller
 */
class Orders extends MainController
{
    /**
     * manage crud operations for orders
     */

    // iniatialize models
    private $order;

    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");            
        }
        // load models 
        $this->order = $this->model('Order');
    }



    // orders home page
    public function home()
    {
    	// iniatilaize data
    	$data = [];

        // get all orders 
        $data['orders'] = $this->order->all();

    	// load view with data
    	$this->view('orders/index', $data);
    }




    public function create()
    {
    	// iniatilaize data
    	$data = [
    		'order_name' => '',
    		'order_name_err' => '',
    	];

    	// check method if post for storing
    	if($_SERVER['REQUEST_METHOD'] == "POST") {
    		// iniatialize data to store
    		$data_to_store = [];

    		// validate
    		$name = post_data('order_name');
    		if(!empty($name)) {
    			$data['order_name'] = $name;
    			if($this->order->checkIfNameExist($name)) {
    				$data['order_name_err'] = "Name of the order entered is already taken";
    			} else {
    				$data_to_store['order_name'] = proper_case($name);
    			}
    		} else {
    			$data['order_name_err'] = "Name of the order is required";
    		}

    		// check for validation errors 
    		if(empty($data['order_name_err'])) {
    			// store data to db and redirect to orders home
                $data_to_store['order_created_by'] = get_sess('logged_in_user_id');
    			$stored = $this->order->add($data_to_store);
    			if($stored) {
                    // set session
                    set_sess("message_success", "order has been added successfully");
                    // redirect
    				redirect_to('orders/show/'.$stored);
    			} else {
    				// load view with data with errors, from db
    				$data['order_name_err'] = "order not added to db, db error, try again";
    				$this->view('orders/create', $data);
    			}
    		} else {
    			// else load view with errors
    			$this->view('orders/create', $data);
    		}
    	} else {
    		// load view
    		$this->view('orders/create', $data);
    	}	
    }





    // order profile, provide id
    public function show($id)
    {
    	// iniatilize data
    	$data = [];

    	// fetch order with id
    	$rol = $this->order->find($id);

    	// check if null is returned
    	if($rol['count']>0) {
    		// not null, load view with order
    		$data['order'] = $rol['data'];
    		$this->view('orders/show', $data);
    	} else {
    		// load 404 with error
    		$data['error'] = "order queried for does not exist";
    		$this->view('errors/404', $data);
    	}
    }





    public function edit($id)
    {
        // initialize data
        $data = [
            'order_name'=> '',
            'order_name_err' => '',
            'order'=>''
        ];


        // get the order to edit
        $order = $this->order->find($id);
        // check if order fetched exists 
        if($order['count']<1) {
            // send to 404 with error
            $data['error'] = "order queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } 

        // load order name in data array
        $data['order'] = $order['data'];
        $data['order_name'] = $order['data']->order_name;
        

        // check if post method
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // iniatialize data to update
            $data_to_update = [];

            // validate and set errors
            $name = post_data('order_name');
            if(strlen($name)) {
                $data['order_name'] = $name;
                if($this->order->checkIfNameExistEdit($id, $name)) {
                    $data['order_name_err'] = "order name is already added";
                } else {
                    $data_to_update['order_name'] = proper_case($name);
                }
            } else {
                $data['order_name_err'] = "order name is required";
            }

            // check if there is any validation errors
            if(empty($data['order_name_err'])) {
                // store
                $updated = $this->order->update($id, $data_to_update);
                if($updated) {
                    // set session message and redirect to orders home
                    set_sess('message_success', "order Updated successfully");
                    redirect_to('orders/show/' . $id);
                } else {
                    // return views with errors
                    $data['order_name_err'] = "order was not updated, db error, please try again";
                    $this->view('orders/edit', $data);
                }
            } else {
                // return views with errors
                $this->view('orders/edit', $data);
            }

        } else {
            // load view if request is get
            $this->view('orders/edit', $data);
        }
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the order to delete
        $rol = $this->order->find($id);
        // check if order fetched exists 
        if($rol['count'] != 1) {
            // send to 404 with error
            $data['error'] = "order queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            // check if request is rolt
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->order->delete($id);
                set_sess("message_info", "order deleted successfully");
                redirect_to('orders');
            } else {
                // load view to confirm delete
                $data['rol'] = $rol['data'];
                $this->view('orders/delete', $data);
            }
        }
    }




































}