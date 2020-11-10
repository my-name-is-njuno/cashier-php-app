<?php  



/**
 * customers controller
 */
class Customers extends MainController
{
    /**
     * manage crud operations for customers
     */

    // iniatialize models
    private $customer;
    private $menu;
    private $order;
    private $receipt;
    private $singleorder;
    private $activity;

    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");            
        }

        // load models 
        $this->customer = $this->model('Customer');
        $this->menu = $this->model('Menu');
        $this->receipt = $this->model('Receipt');
        $this->order = $this->model('Order');
        $this->singleorder = $this->model('Singleorder');
        $this->activity = $this->model('Activity');

    }



    // customers home page
    public function home($page = 1)
    {
    	// initialize data
        $data = [];


        // set page
        if(!$page) {$page = 1;}
        

        // get paginated data
        $dt = $this->singleorder->getAllPaginated(10, $page);
        // check if anything has been returned
        if($dt) {
            $total_pages = $dt[0];
            $singleorders_data = $dt[1];
            $total_results = $dt[2];
        } else {
            $total_pages = 0;
            $singleorders_data = [];
            $total_results = 0;
        }

        // get all singleorders
        $data['singleorders'] = $singleorders_data;
        $data['total_results'] = $total_results;
        $data['total_pages'] = $total_pages;
  
        


    	// load view with data
    	$this->view('customers/index', $data);

    }




    public function create()
    {
    	// iniatilaize data
    	$data = [
    		'customer_name' => '',
    		'customer_name_err' => '',
            'customer_contacts' => '',
            'customer_contacts_err' => '',
            'receipt_paid' => '',
            'receipt_paid_err' => '',
            'receipt_balance' => '',
            'receipt_balance_err' => '',
            'singleorder_table' => '',
            'singleorder_table_err' => '',
            'form_err' => ''
    	];

       

        $data['menus'] = $this->menu->all();

    	// check method if post for storing
    	if($_SERVER['REQUEST_METHOD'] == "POST") {

            



    		// iniatialize data to store
    		$data_to_store = [];
            $data_to_store_order = [];
            $data_to_store_receipt = [];
            $data_to_store_singleorder = [];
            $error = false;

    		// validate
    		$name = post_data('customer_name');
    		if(!empty($name)) {
    			$data['customer_name'] = $name;
    			$data_to_store['customer_name'] = proper_case($name);
    		} 

            $contacts = post_data('customer_contacts');
            if(!empty($contacts)) {
                $data['customer_contacts'] = $contacts;
                $data_to_store['customer_contacts'] = proper_case($contacts);
            } 

            


            $receipt_paid = post_data('receipt_paid');
            if(!empty($receipt_paid)) {
                $data['receipt_paid'] = $receipt_paid;
                $data_to_store_receipt['receipt_paid'] = proper_case($receipt_paid);
            } else {
                $error = true;
                $data['receipt_paid_err'] = "Enter Amount Paid";
            }



            $singleorder_table = post_data('singleorder_table');
            if(!empty($singleorder_table)) {
                $data['singleorder_table'] = $singleorder_table;
                $data_to_store_singleorder['singleorder_table'] = proper_case($singleorder_table);
            } else {
                $error = true;
                $data['singleorder_table_err'] = "Enter type of order";
            }



            $receipt_balance = post_data('receipt_balance');
            if(!empty($receipt_balance)) {
                $data['receipt_balance'] = $receipt_balance;
                $data_to_store_receipt['receipt_balance'] = proper_case($receipt_balance);
            } 


            $total = post_data('receipt_total_amount');
            if(!empty($total)) {
                $data['receipt_total_amount'] = $total;
                $data_to_store_receipt['receipt_total_amount'] = proper_case($total);
                $data_to_store_singleorder['singleorder_total'] = proper_case($total);
            }





            $menus_chosen = $_POST['menus'];
            if(!isset($menus_chosen)) {
                $data['form_err'] = "No menu selected, you have to select at least one";
                $this->view('customers/create', $data);
                return;
            }

            

    		// check for validation errors 
    		if(!$error) {
    			// store data to db and redirect to customers home
                $data_to_store['customer_by'] = get_sess('logged_in_user_id');
    			$stored = $this->customer->add($data_to_store);
    			if($stored) {
                    // store sigle order
                    $data_to_store_singleorder['singleorder_customer_id'] = $stored;
                    $data_to_store_singleorder['singleorder_by'] = get_sess('logged_in_user_id');
                    $stored_singleorder = $this->singleorder->add($data_to_store_singleorder);

                    $stored_order = false;
                    if($stored_singleorder) {
                        foreach ($menus_chosen as $v) {
                            $data_to_store_order['order_menu_id'] = $v;
                            $data_to_store_order['order_customer_id'] = $stored;
                            $data_to_store_order['order_singleorder_id'] = $stored_singleorder;
                            $data_to_store_order['order_by'] = get_sess('logged_in_user_id');
                            $data_to_store_order['order_price_per_item'] = post_data('order_price_per_item_'.$v);
                            $data_to_store_order['order_quantity'] = post_data('order_quantity_'.$v);
                            $data_to_store_order['order_total'] = post_data('order_total_'.$v);
                            $stored_order = $this->order->add($data_to_store_order);
                        }

                        if($stored_order) {
                            $data_to_store_receipt['receipt_customer_id'] = $stored;
                            $data_to_store_receipt['receipt_singleorder_id'] = $stored_singleorder;
                            $data_to_store_receipt['receipt_by'] = get_sess('logged_in_user_id');

                            $stored_receipt = $this->receipt->add($data_to_store_receipt);

                            if($stored_receipt) {
                                // set session
                                set_sess("message_success", "customer's order has been added successfully");
                                // redirect
                                redirect_to('customers/show/'.$stored_singleorder);

                                $data_activity = [
                                    'activity_user_id' => get_sess('logged_in_user_id'),
                                    'activity_description' => "Added new customer order - order number $stored_singleorder",
                                ];

                                $activity_stored = $this->activity->add($data_activity);
                            } else {
                               // load view with data with errors, from db
                                $this->customer->delete($stored);
                                $this->singleorder->delete($stored_singleorder);
                                $data['form_err'] = "receipt not added, please try again, db error, try again";
                                $this->view('customers/create', $data); 
                            }
                        } else {
                            // load view with data with errors, from db
                            $this->customer->delete($stored);
                            $this->singleorder->delete($stored_singleorder);
                            $data['form_err'] = "customer and receipt not added, please try again, db error, try again";
                            $this->view('customers/create', $data); 
                        }
                    } else {
                        // load view with data with errors, from db
                        $this->customer->delete($stored);     
                        $data['form_err'] = "customer not added, please try again, db error, try again";
                        $this->view('customers/create', $data); 
                    }
    			} else {
    				// load view with data with errors, from db
    				$data['form_err'] = "customer not added to db, db error, try again";
    				$this->view('customers/create', $data);
    			}
    		} else {
    			// else load view with errors
    			$this->view('customers/create', $data);
    		}
    	} else {
    		// load view
    		$this->view('customers/create', $data);
    	}	
    }





    // customer profile, provide id
    public function show($id)
    {
    	// iniatilize data
    	$data = [];
    	// fetch customer with id
    	$singleorder = $this->singleorder->find($id);
    	// check if null is returned
    	if($singleorder['count']>0) {
    		// not null, load view with customer
    		$data['singleorder'] = $singleorder['data'];

            // load orders for this single order
            $data['orders'] = $this->order->get_orders_for_sigleorder($singleorder['data']->id);
            // get receipt
            $data['receipt'] = $this->receipt->get_receipt_for_sigleorder($singleorder['data']->id)['data'];
            // get customer
            $data['customer'] = $this->customer->find($singleorder['data']->singleorder_customer_id)['data'];
            // show
    		$this->view('customers/show', $data);
    	} else {
    		// load 404 with error
    		$data['error'] = "customer queried for does not exist";
    		$this->view('errors/404', $data);
            return;
    	}
    }





    public function edit($id)
    {
        // iniatilaize data
        $data = [
            'customer_name' => '',
            'customer_name_err' => '',
            'customer_contacts' => '',
            'customer_contacts_err' => '',
            'receipt_paid' => '',
            'receipt_paid_err' => '',
            'receipt_balance' => '',
            'receipt_balance_err' => '',
            'singleorder_table' => '',
            'singleorder_table_err' => '',
            'form_err' => ''
        ];

       

        $data['menus'] = $this->menu->all();

        // get single order
        $data['singleorder'] = $this->singleorder->find($id)['data'];
        $data['singleorder_table'] = $data['singleorder']->singleorder_table;


        // get customer
        $data['customer'] = $this->customer->find($data['singleorder']->singleorder_customer_id)['data'];
        $data['customer_name'] = $data['customer']->customer_name;
        $data['customer_contacts'] = $data['customer']->customer_contacts;


        // get receipt
        $data['receipt'] = $this->receipt->get_receipt_for_sigleorder($id)['data'];
        $data['receipt_paid'] = $data['receipt']->receipt_paid;
        $data['receipt_balance'] = $data['receipt']->receipt_balance;



        // get already ordered menues
        $data['ordered_menus'] = $this->order->get_orders_for_sigleorder($id)['data'];



        // check method if post for storing
        if($_SERVER['REQUEST_METHOD'] == "POST") {

            
            // iniatialize data to store
            $data_to_update = [];
            $data_to_update_order = [];
            $data_to_update_receipt = [];
            $data_to_update_singleorder = [];
            $error = false;

            // validate
            $name = post_data('customer_name');
            if(!empty($name)) {
                $data['customer_name'] = $name;
                $data_to_update['customer_name'] = proper_case($name);
            } 

            $contacts = post_data('customer_contacts');
            if(!empty($contacts)) {
                $data['customer_contacts'] = $contacts;
                $data_to_update['customer_contacts'] = proper_case($contacts);
            } 


            $singleorder_table = post_data('singleorder_table');
            if(!empty($singleorder_table)) {
                $data['singleorder_table'] = $singleorder_table;
                $data_to_update_singleorder['singleorder_table'] = proper_case($singleorder_table);
            } else {
                $error = true;
                $data['singleorder_table_err'] = "Enter type of order";
            }

            


            $receipt_paid = post_data('receipt_paid');
            if(isset($receipt_paid)) {
                $data['receipt_paid'] = $receipt_paid;
                $data_to_update_receipt['receipt_paid'] = proper_case($receipt_paid);
            } else {
                $error = true;
                $data['receipt_paid_err'] = "Enter Amount Paid";
            }


            $receipt_balance = post_data('receipt_balance');
            if(isset($receipt_balance)) {
                $data['receipt_balance'] = $receipt_balance;
                $data_to_update_receipt['receipt_balance'] = proper_case($receipt_balance);
            } else {
                $data_to_update_receipt['receipt_balance'] = 0;
            } 




            $total = post_data('receipt_total_amount');
            if(isset($total)) {
                $data['receipt_total_amount'] = $total;
                $data_to_update_receipt['receipt_total_amount'] = proper_case($total);
                $data_to_update_singleorder['singleorder_total'] = proper_case($total);
            }





            $menus_chosen = $_POST['menus'];
            if(!isset($menus_chosen)) {
                $data['form_err'] = "No menu selected, you have to select at least one";
                $this->view('customers/create', $data);
                return;
            }

            

            // check for validation errors 
            if(!$error) {
                // store data to db and redirect to customers home
                $data_to_update['customer_by'] = get_sess('logged_in_user_id');
                $updated = $this->customer->update($data['customer']->id,$data_to_update);
                if($updated) {
                    // store sigle order
                    $data_to_update_singleorder['singleorder_customer_id'] = $updated;
                    $data_to_update_singleorder['singleorder_by'] = get_sess('logged_in_user_id');

                    $updated_singleorder = $this->singleorder->update($id, $data_to_update_singleorder);

                    $updated_order = false;
                    if($updated_singleorder) {
                            foreach ($data['ordered_menus'] as $value) {
                                $this->order->delete($value->id);
                            }
                            foreach ($menus_chosen as $v) {
                                // check if it exists
                                

                                    $data_to_update_order['order_menu_id'] = $v;
                                    $data_to_update_order['order_customer_id'] = $data['customer']->id;
                                    $data_to_update_order['order_singleorder_id'] = $id;
                                    $data_to_update_order['order_by'] = get_sess('logged_in_user_id');
                                    $data_to_update_order['order_price_per_item'] = post_data('order_price_per_item_'.$v);
                                    $data_to_update_order['order_quantity'] = post_data('order_quantity_'.$v);
                                    $data_to_update_order['order_total'] = post_data('order_total_'.$v);
                                    $updated_order = $this->order->add($data_to_update_order);

                                
                                
                            }

                            if($updated_order) {
                                $updated_receipt = $this->receipt->update($data['receipt']->id, $data_to_update_receipt);
                                if($updated_receipt) {
                                    // set session
                                    set_sess("message_success", "Customer's order has been updated successfully");
                                    // redirect
                                    redirect_to('customers/show/'.$updated_singleorder);
                                } else {
                                   // load view with data with errors, from db
                                    $data['form_err'] = "Receipt not updated, please try again, db error, try again";
                                    $this->view('customers/edit', $data); 
                                }
                            } else {
                                // load view with data with errors, from db
                                $data['form_err'] = "customer and receipt not updated, please try again, db error, try again";
                                $this->view('customers/edit', $data); 
                            }
                    } else {
                        // load view with data with errors, from db
                             
                        $data['form_err'] = "customer not updated, please try again, db error, try again";
                        $this->view('customers/edit', $data); 
                    }
                } else {
                    // load view with data with errors, from db
                    $data['form_err'] = "customer not updated to db, db error, try again";
                    $this->view('customers/edit', $data);
                }
            } else {
                // else load view with errors
                $this->view('customers/edit', $data);
            }
        } else {
            // load view
            $this->view('customers/edit', $data);
        }   
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the customer to delete
        $rol = $this->customer->find($id);
        // check if customer fetched exists 
        if($rol['count'] != 1) {
            // send to 404 with error
            $data['error'] = "customer queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            // check if request is rolt
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->customer->delete($id);
                set_sess("message_info", "customer deleted successfully");
                redirect_to('customers');
            } else {
                // load view to confirm delete
                $data['rol'] = $rol['data'];
                $this->view('customers/delete', $data);
            }
        }
    }








    public function recievebalance($id)
    {
        // initiate data
        $data = [
            'customer_name' => '',
            'customer_name_err' => '',
            'customer_contacts' => '',
            'customer_contacts_err' => '',
            'receipt_paid' => '',
            'receipt_paid_err' => '',
            'received' => '',
            'received_err' => '',
            'receipt_balance' => '',
            'receipt_balance_err' => '',
            'singleorder_table' => '',
            'singleorder_table_err' => '',
            'form_err' => ''
        ] ;


        // get single order
        $data['singleorder'] = $this->singleorder->find($id)['data'];
        $data["singleorder_table"] = $data['singleorder']->singleorder_table;


        // get customer
        $data['customer'] = $this->customer->find($data['singleorder']->singleorder_customer_id)['data'];
        $data['customer_name'] = $data['customer']->customer_name;
        $data['customer_contacts'] = $data['customer']->customer_contacts;


        // get receipt
        $data['receipt'] = $this->receipt->get_receipt_for_sigleorder($id)['data'];
        $data['receipt_paid'] = $data['receipt']->receipt_paid;
        $data['receipt_balance'] = $data['receipt']->receipt_balance;



        $data['menus'] = $this->menu->all();


        $data['order_menus'] = $this->order->get_orders_for_sigleorder($id);


        if($_SERVER['REQUEST_METHOD'] == "POST") {

            //check if balance is added
            $data_to_update = [];
            $error = false;

            $balance_received = post_data('received');
            if(strlen($balance_received)) {
                $data['received'] = $balance_received;
                $already_paid = $data['receipt']->receipt_paid;
                $receipt_total_paid = $already_paid + $balance_received;
                $data_to_update['receipt_paid'] = $receipt_total_paid; 
                $data_to_update['receipt_balance'] = $data['receipt_total_amount'] - $receipt_total_paid;
            } else {
                $data['received_err'] = "Received amount is required";
                $error = true;
            }



            



            if (!$error) {
                // update receipt amounts

                $updated = $this->receipt->update($data['receipt']->id, $data_to_update);
                if($updated) {
                    set_sess('message_success', 'Balance amount updated successfully');
                    redirect_to('customers/show/'.$id);
                } else {
                    $data['form_err'] = "Balance amount not updated, try again";
                    $this->view('customers/receivebalance', $data);
                }
            } else {
                $data['form_err'] = "Rectify errors and try again";
                $this->view('customers/receivebalance', $data);
            }
        } else {
            $this->view('customers/receivebalance', $data);
        }

    }
}