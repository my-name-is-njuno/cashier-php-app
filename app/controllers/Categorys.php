<?php



/**
 * categorys controller
 */
class Categorys extends MainController
{
    /**
     * manage crud operations for categorys
     */

    // iniatialize models
    private $category;
    private $menu;

    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");
        }

        // load models
        $this->category = $this->model('Category');
        $this->menu = $this->model('Menu');

    }



    // categorys home page
    public function home()
    {
    	// iniatilaize data
    	$data = [];
      // get all categorys
      $data['categorys'] = $this->category->getAll();
    	// load view with data
    	$this->view('categorys/index', $data);
    }




    public function create()
    {
    	// iniatilaize data
    	$data = [
    		'category_name' => '',
    		'category_name_err' => '',
    	];

    	// check method if post for storing
    	if($_SERVER['REQUEST_METHOD'] == "POST") {
    		// iniatialize data to store
    		$data_to_store = [];

    		// validate
    		$name = post_data('category_name');
    		if(!empty($name)) {
    			$data['category_name'] = $name;
    			if($this->category->checkIfNameExist($name)) {
    				$data['category_name_err'] = "Name of the category entered is already taken";
    			} else {
    				$data_to_store['category_name'] = proper_case($name);
    			}
    		} else {
    			$data['category_name_err'] = "Name of the category is required";
    		}

    		// check for validation errors
    		if(empty($data['category_name_err'])) {
    			// store data to db and redirect to categorys home
    			$stored = $this->category->add($data_to_store);
    			if($stored) {
                    // set session
                    set_sess("message_success", "category has been added successfully");
                    // redirect
    				redirect_to('categorys/show/'. $stored);
    			} else {
    				// load view with data with errors, from db
    				$data['category_name_err'] = "category not added to db, db error, try again";
    				$this->view('categorys/create', $data);
    			}
    		} else {
    			// else load view with errors
    			$this->view('categorys/create', $data);
    		}
    	} else {
    		// load view
    		$this->view('categorys/create', $data);
    	}
    }





    // category profile, provide id
    public function show($id)
    {
    	// iniatilize data
    	$data = [];

    	// fetch category with id
    	$category = $this->category->find($id);
    	// check if null is returned
    	if($category['count']>0) {
    		$this->view('categorys/show', $data);
    	} else {
    		// load 404 with error
    		$data['error'] = "category queried for does not exist";
    		$this->view('errors/404', $data);
    	}
    }





    public function edit($id)
    {
        // initialize data
        $data = [
            'category_name'=> '',
            'category_name_err' => '',
            'category'=>''
        ];


        // get the category to edit
        $category = $this->category->find($id);
        // check if category fetched exists
        if($category['count']<1) {
            // send to 404 with error
            $data['error'] = "category queried for does not exist";
            $this->view('errors/404', $data);
            return;
        }

        // load category name in data array
        $data['category'] = $category['data'];
        $data['category_name'] = $category['data']->category_name;


        // check if post method
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // iniatialize data to update
            $data_to_update = [];

            // validate and set errors
            $name = post_data('category_name');
            if(strlen($name)) {
                $data['category_name'] = $name;
                if($this->category->checkIfNameExistEdit($id, $name)) {
                    $data['category_name_err'] = "category name is already added";
                } else {
                    $data_to_update['category_name'] = proper_case($name);
                }
            } else {
                $data['category_name_err'] = "category name is required";
            }

            // check if there is any validation errors
            if(empty($data['category_name_err'])) {
                // store
                $updated = $this->category->update($id, $data_to_update);
                if($updated) {
                    // set session message and redirect to categorys home
                    set_sess('message_success', "category Updated successfully");
                    redirect_to('categorys/show/' . $id);
                } else {
                    // return views with errors
                    $data['category_name_err'] = "category was not updated, db error, please try again";
                    $this->view('categorys/edit', $data);
                }
            } else {
                // return views with errors
                $this->view('categorys/edit', $data);
            }

        } else {
            // load view if request is get
            $this->view('categorys/edit', $data);
        }
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the category to delete
        $category = $this->category->find($id);
        // check if category fetched exists
        if($category['count'] != 1) {
            // send to 404 with error
            $data['error'] = "category queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            // check if category has items
            $items = $this->menu->get_menu_items_for_category($id);
            $data['items'] = $items;
            // check if request is rolt
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->category->delete($id);
                set_sess("message_info", "category deleted successfully");
                redirect_to('categorys');
            } else {
                // load view to confirm delete
                $data['category'] = $category['data'];
                $this->view('categorys/delete', $data);
            }
        }
    }
}
