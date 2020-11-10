<?php  



/**
 * menus controller
 */
class Menus extends MainController
{
    /**
     * manage crud operations for menus
     */

    // iniatialize models
    private $menu;
    private $category;
    private $order;
    private $receipt;


    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");            
        }
        // load models 
        $this->menu = $this->model('Menu');
        $this->category = $this->model('Category');
        $this->receipt = $this->model('Receipt');
        $this->order = $this->model('Order');
    }



    // menus home page
    public function home($page=1)
    {
    	// initialize data
        $data = [];


        // set page
        if(!$page) {$page = 1;}
        

        // get paginated data
        $dt = $this->menu->getAllPaginated(10, $page);
        // check if anything has been returned
        if($dt) {
            $total_pages = $dt[0];
            $menus_data = $dt[1];
            $total_results = $dt[2];
        } else {
            $total_pages = 0;
            $menus_data = [];
            $total_results = 0;
        }

        // get all menus
        $data['menus'] = $menus_data;
        $data['total_results'] = $total_results;
        $data['total_pages'] = $total_pages;

    	// load view with data
    	$this->view('menus/index', $data);
    }




    public function create()
    {
    	// iniatilaize data
    	$data = [
            'form_error' => '',
    		'menu_item' => '',
    		'menu_item_err' => '',
            'menu_image' => '',
            'menu_image_err' => '',
            'menu_category_id' => '',
            'menu_category_id_err' => '',
            'menu_description' => '',
            'menu_description_err' => '',
            'menu_cost' => '',
            'menu_cost_err' => '',
            'menu_price' => '',
            'menu_price_err' => '',
    	];

        // get categories
        $cats = $this->category->getAll();
        if($cats['count'] == 0) {
            set_sess('message_info', 'Before adding menu item, first add menu category here');
            redirect_to('categorys/create');
            return;
        }

        $data['categorys'] = $cats['data'];





    	// check method if post for storing
    	if($_SERVER['REQUEST_METHOD'] == "POST") {
    		// iniatialize data to store
    		$data_to_store = [];

            // track errors
            $errors = false;

    		// validate
    		$item = post_data('menu_item');
    		if(!empty($item)) {
    			$data['menu_item'] = $item;
    			if($this->menu->checkIfItemExist($item)) {
    				$data['menu_item_err'] = "item of the menu entered is already taken";
                    $error = true;
    			} else {
    				$data_to_store['menu_item'] = proper_case($item);
    			}
    		} else {
    			$data['menu_item_err'] = "item of the menu is required";
                $error = true;
    		}


            $img = $_FILES['menu_image'];
            if($img['size'] > 0) {
                  $handle = new \Verot\Upload\Upload($_FILES['menu_image']);
                  $new_name = strtolower(slugify($item)).time();
                  $handle->file_new_name_body   = $new_name ;
                  $handle->image_resize         = true;
                  $handle->image_x              = 400;
                  $handle->image_y              = 300;
                  $handle->process(get_img_path().'menu/');
                  if ($handle->processed) {
                    $data_to_store['menu_image'] = $new_name  . ".".$handle->file_dst_name_ext;
                    $handle->clean();
                  } else {
                    $data['menu_image_err'] = $handle->error;
                    $error = true;
                  }          
            } else {
                $data['menu_image_err'] = "Menu for the image is required";
                $error = true;
            }



            $category_id = post_data('menu_category_id');
            if(!empty($category_id)) {
                $data['menu_category_id'] = $category_id;
                $data_to_store['menu_category_id'] = proper_case($category_id);
            } else {
                $data['menu_category_id_err'] = "category of the menu is required";
                $error = true;
            }


            $description = post_data('menu_description');
            if(!empty($description)) {
                $data['menu_description'] = $description;
                $data_to_store['menu_description'] = proper_case($description);
            } else {
                $data['menu_description_err'] = "description of the menu is required";
                $error = true;
            }


            $cost = post_data('menu_cost');
            if(!empty($cost)) {
                $data['menu_cost'] = $cost;
                $data_to_store['menu_cost'] = proper_case($cost);
            } else {
                $data['menu_cost_err'] = "cost of the menu is required";
                $error = true;
            }


            $price = post_data('menu_price');
            if(!empty($price)) {
                $data['menu_price'] = $price;
                $data_to_store['menu_price'] = proper_case($price);
            } else {
                $data['menu_price_err'] = "price of the menu is required";
                $error = true;
            }

    		// check for validation errors 
    		if(!$error) {
    			// store data to db and redirect to menus home
                $data_to_store['menu_by'] = get_sess('logged_in_user_id');
    			$stored = $this->menu->add($data_to_store);
    			if($stored) {
                    // set session
                    set_sess("message_success", "Menu has been added successfully");
                    // redirect
    				redirect_to('menus/show/'.$stored);
    			} else {
    				// load view with data with errors, from db
    				$data['menu_name_err'] = "menu not added to db, db error, try again";
    				$this->view('menus/create', $data);
    			}
    		} else {
    			// else load view with errors
    			$this->view('menus/create', $data);
    		}
    	} else {
    		// load view
    		$this->view('menus/create', $data);
    	}	
    }





    // menu profile, provide id
    public function show($id)
    {
    	// iniatilize data
    	$data = [];

    	// fetch menu with id
    	$menu = $this->menu->find($id);

    	// check if null is returned
    	if($menu['count']>0) {
            // check if menu has sales
            $sales = $this->order->get_orders_for($id);
            $data['customers'] = $sales;

            // not null, load view with menu
    		$data['menu'] = $menu['data'];
            $data['sales'] = $this->order->get_for_item($id)['data'];

    		$this->view('menus/show', $data);
    	} else {
    		// load 404 with error
    		$data['error'] = "menu queried for does not exist";
    		$this->view('errors/404', $data);
            return;
    	}
    }





    public function edit($id)
    {
        // initialize data
        $data = [
            'form_error' => '',
            'menu_item' => '',
            'menu_item_err' => '',
            'menu_image' => '',
            'menu_image_err' => '',
            'menu_category_id' => '',
            'menu_category_id_err' => '',
            'menu_description' => '',
            'menu_description_err' => '',
            'menu_cost' => '',
            'menu_cost_err' => '',
            'menu_price' => '',
            'menu_price_err' => '',
        ];



        // get categories
        $data['categorys'] = $this->category->all();


        // get the menu to edit
        $menu = $this->menu->find($id);
        // check if menu fetched exists 
        if($menu['count']<1) {
            // send to 404 with error
            $data['error'] = "menu queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } 

        // load menu name in data array
        $data['menu'] = $menu['data'];
        $data['menu_item'] = $menu['data']->menu_item;
        $data['menu_cost'] = $menu['data']->menu_cost;
        $data['menu_price'] = $menu['data']->menu_price;
        $data['menu_description'] = $menu['data']->menu_description;
        $data['menu_category_id'] = $menu['data']->menu_category_id;
        

        // check if post method
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // iniatialize data to update
            $data_to_update = [];

            // validate and set errors
            // track errors
            $errors = false;

            // validate
            $item = post_data('menu_item');
            if(!empty($item)) {
                $data['menu_item'] = $item;
                if($this->menu->checkIfItemExistEdit($id, $item)) {
                    $data['menu_item_err'] = "item of the menu entered is already taken";
                    $error = true;
                } else {
                    $data_to_update['menu_item'] = proper_case($item);
                }
            } else {
                $data['menu_item_err'] = "item of the menu is required";
                $error = true;
            }


            $img = $_FILES['menu_image'];
            if($img['size'] > 0) {
                  $handle = new \Verot\Upload\Upload($_FILES['menu_image']);
                  $new_name = strtolower(slugify($item)).time();
                  $handle->file_new_name_body   = $new_name ;
                  $handle->image_resize         = true;
                  $handle->image_x              = 400;
                  $handle->image_y              = 300;
                  $handle->process(get_img_path().'menu/');
                  if ($handle->processed) {
                    $data_to_update['menu_image'] = $new_name  . ".".$handle->file_dst_name_ext;
                    $handle->clean();
                  } else {
                    $data['menu_image_err'] = $handle->error;
                    $error = true;
                  }          
            } else {
                $data['menu_image_err'] = "Menu for the image is required";
                $error = true;
            }


            $category_id = post_data('menu_category_id');
            if(!empty($category_id)) {
                $data['menu_category_id'] = $category_id;
                $data_to_update['menu_category_id'] = proper_case($category_id);
            } else {
                $data['menu_category_id_err'] = "category of the menu is required";
                $error = true;
            }


            $description = post_data('menu_description');
            if(!empty($description)) {
                $data['menu_description'] = $description;
                $data_to_update['menu_description'] = proper_case($description);
            } else {
                $data['menu_description_err'] = "description of the menu is required";
                $error = true;
            }


            $cost = post_data('menu_cost');
            if(!empty($cost)) {
                $data['menu_cost'] = $cost;
                $data_to_update['menu_cost'] = proper_case($cost);
            } else {
                $data['menu_cost_err'] = "cost of the menu is required";
                $error = true;
            }


            $price = post_data('menu_price');
            if(!empty($price)) {
                $data['menu_price'] = $price;
                $data_to_update['menu_price'] = proper_case($price);
            } else {
                $data['menu_price_err'] = "price of the menu is required";
                $error = true;
            }


            // check if there is any validation errors
            if(empty($data['menu_name_err'])) {
                // store
                $updated = $this->menu->update($id, $data_to_update);
                if($updated) {
                    // set session message and redirect to menus home
                    set_sess('message_success', "menu Updated successfully");
                    redirect_to('menus/show/' . $id);
                } else {
                    // return views with errors
                    $data['menu_name_err'] = "menu was not updated, db error, please try again";
                    $this->view('menus/edit', $data);
                }
            } else {
                // return views with errors
                $this->view('menus/edit', $data);
            }

        } else {
            // load view if request is get
            $this->view('menus/edit', $data);
        }
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the menu to delete
        $menu = $this->menu->find($id);
        // check if menu fetched exists 
        if($menu['count'] != 1) {
            // send to 404 with error
            $data['error'] = "menu queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            // check if menu has sales
            $sales = $this->order->get_orders_for($menu['data']->id);
            $data['sales'] = $sales;
            // check if request is menut
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->menu->delete($id);
                set_sess("message_info", "menu deleted successfully");
                redirect_to('menus');
            } else {
                // load view to confirm delete
                $data['menu'] = $menu['data'];
                $this->view('menus/delete', $data);
            }
        }
    }













    public function find_with_ajax()
    {
        $query = post_data('query');
        $data = $this->menu->find_with_ajax($query);

        $return_arr = [];
        if($data['count'] > 0) {
            $data_to_return = $data['data'];
            $return_arr['price'] = $data_to_return->menu_price;            
        } 

        echo json_encode($return_arr);
    }



























    











    
}