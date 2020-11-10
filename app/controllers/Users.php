<?php 

/**
 * summary
 */
class Users extends MainController
{
	// iniatialize models
   	private $user;
    private $role;
    private $category;
    private $menu;
    private $activity;
    private $singleorder;
    private $receipt;

    public function __construct()
    {
    	// load models
        $this->user = $this->model('User');
        $this->role = $this->model('Role');
        $this->category = $this->model('Category');
        $this->menu = $this->model('Menu');
        $this->activity = $this->model('Activity');
        $this->singleorder = $this->model('Singleorder');
        $this->receipt = $this->model('Receipt');
    }


    // users home page
    public function home()
    {
        $data = [];
        $data['users'] = $this->user->all();
    	$this->view('users/index', $data);
    }



    // create / register users

    public function create() 
    {


        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");      
            return ;     
        }


        $id = get_sess('logged_in_user_id');


        $user = $this->user->find($id)['data'];

        if($user->user_role_id != 1) {
            set_sess("message_error", "You don't have authority to add new users");
            redirect_to("errors/401");      
            return ;   
        }


        // iniatilize data container
        $data = [
            'form_err'=> '',
            'user_name'=> '',
            'user_name_err' => '',
            'user_email' => '',
            'user_email_err' => '',
            'user_password' => '',
            'user_password_err' => '',
            'user_confirm_password' => '',
            'user_confirm_password_err' => '',
            'user_role' => '',
            'user_role_err' => '',

        ];


        // roles

        $data['roles'] = $this->role->getAll();

        



        // check method if post for storing
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            // iniatialize data to store
            $data_to_store = [];

            // error
            $error = false;

            // validate
            $name = post_data('user_name');
            if(!empty($name)) {
                $data['user_name'] = $name;
                if($this->user->checkIfNameExist($name)) {
                    $data['user_name_err'] = "name is already taken";
                    $error = true;
                } else {
                    $data_to_store['user_name'] = proper_case($name);
                }
            } else {
                $data['user_name_err'] = "Name is required";
                $error = true;
            }

            // validate email
            $email = post_data('user_email');
            if(!empty($email)) {
                $data['user_email'] = $email;
                if($this->user->checkIfEmailExist($email)) {
                    $data['user_email_err'] = "email is already taken";
                    $error = true;
                } else {
                    $data_to_store['user_email'] = strtolower($email);
                }
            } else {
                $data['user_email_err'] = "Email is required";
                $error = true;
            }


            // validate password
            $password = post_data('user_password');
            if(!empty($password) && strlen($password) > 6) {
                $data['user_password'] = $password;
                $data_to_store['user_password'] = password_hash($password, PASSWORD_DEFAULT);   
            } else {
                $data['user_password_err'] = "Password is required and must be at least 6 characters";
                $error = true;
            }


            
            // validate role
            $role = post_data('user_role');
            if(isset($role)) {
                $data['user_role'] = $role;
                $data_to_store['user_role_id'] = $role;   
                // die($role);
            } else {
                $data['user_role_err'] = "User main role is required";
                $error = true;
            }


            

            // check for validation errors 
            if(!$error) {

                // save roles 
                // store data to db and redirect to users home
                $stored = $this->user->add($data_to_store);
                if($stored) {
                    // set session
                    set_sess("message_success", "User has been added successfully");
                    // redirect
                    redirect_to('users/profile',$stored);
                } else {
                    // load view with data with errors, from db
                    $data['form_err'] = "Role not added to db, db error, try again";
                    $this->view('users/register', $data);
                }
            } else {
                // else load view with errors
                $this->view('users/register', $data);
            }
        } else {
            // load view
            $this->view('users/register', $data);
        }   

    }
















    public function login()
    {
        // initiliaze data
        $data = [
            // 'user_name' => '',
            'user_email' => '',
            'user_email_err' => '',
            'user_password' => '',
            'user_password_err' => '',
        ];

        // check if post request
        if($_SERVER['REQUEST_METHOD'] == "POST") {

            // initilize user login data to counter check with
            $user = '';

            // validate submitted data
            $email = post_data('user_email');
            if(!empty($email)) {
                $data['user_email'] = $email;
                // check if email is okey
                $user = $this->user->checkIfEmailExist($email);
                if($user) {
                    $password = post_data('user_password');
                    if(!empty($password)) {
                        $data['user_password'] = $password;
                        if(password_verify($password, $user->user_password)) {
                            if ($user->user_active) {
                              login_user($user->id, $user->user_name, $user->user_email, $user->user_role_id);
                                redirect_to('');  
                            } else {
                              $data['user_email_err'] = "Account is deactivated, request admin for activation";  
                            }
                            
                        } else {
                            $data['user_email_err'] = "wrong email / password combo";
                        }
                    } else {
                        $data['user_password_err'] = "Password of the user is required";
                    }
                } else {
                    $data['user_email_err'] = "wrong email / password combo";
                }
            } else {
                $data['user_email_err'] = "Email of the user is required";
            }
            $this->view('users/login', $data);
        } else {
            $this->view('users/login', $data);
        }

    }













    public function show($id)
    {
        // iniatilize data
        $data = [];

        // fetch user with id
        $user = $this->user->get_user_for_show($id);
        $data['userroles'] = $this->user->getUsersRoles($id);

        // check if null is returned
        if($user) {
            // not null, load view with user
            $data['user'] = $user;
            $this->view('users/show', $data);
        } else {
            // load 404 with error
            $data['error'] = "User queried for does not exist";
            $this->view('errors/404', $data);
        }
    }








    public function edit($id)
    {
        // iniatilize data container
        $data = [
            'user_name'=> '',
            'user_name_err' => '',
            'user_password' => '',
            'user_password_err' => '',
            'user_confirm_password' => '',
            'user_confirm_password_err' => '',
           
        ];

    


        // get user to edit
        $user = $this->user->find($id);

        // check if null is returned
        if($user['count']>0) {
        } else {
            // load 404 with error
            $data['error'] = "User queried for does not exist";
            $this->view('errors/404', $data);
        }

        // pass user to view
        $data['user'] = $user['data'];


        // check method if post for storing
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            // iniatialize data to store
            $data_to_update = [];
            $error = false;

            // validate
            $name = post_data('user_name');
            if(!empty($name)) {
                $data['user_name'] = $name;
                if($this->user->checkIfNameExistEdit($id, $name)) {
                    $data['user_name_err'] = "name is already taken";
                } else {
                    $data_to_update['user_name'] = $name;
                    $error = true;
                }
            } else {
                $data['user_name_err'] = "Name is required";
                $error = true;
            }


            // validate password
            $password = post_data('user_password');
            if(!empty($password) && strlen($password) > 6) {
                $data['user_password'] = $password;
                $data_to_update['user_password'] = password_hash($password, PASSWORD_DEFAULT);   
            } else {
                $data['user_password_err'] = "Password is required and must be at least 6 characters";
                $error = true;
            }


            // validate confirm_password
            $confirm_password = post_data('user_confirm_password');
            if(!empty($confirm_password) && strlen($confirm_password) > 6 && $confirm_password == $password) {
                $data['user_confirm_password'] = $confirm_password;
            } else {
                $data['user_confirm_password_err'] = "Confirm Password is required and must be at least 6 characters and have to match password";
                $error = true;
            }


            



            // check for validation errors 
            if(!$error) {
                // store data to db and redirect to users home
                $updated = $this->user->update($id, $data_to_update);
                if($updated) {
                    // set session
                    set_sess("message_success", "User has been updated successfully");
                    // redirect
                    redirect_to('users/settings/'.$id);
                } else {
                    // load view with data with errors, from db
                    set_sess("message_error", "User not updated to db, db error, try again");
                    redirect_to('users/settings/'.$id);
                }
            } else {
                // else load view with errors
                // load view with data with errors, from db
                set_sess("message_error", "errors in form");
                echo '<pre>';
                die(print_r($data));
                echo '</pre>';
                redirect_to('users/settings/'.$id);
            }
        } else {
            // load view
            redirect_to('users/settings/'.$id);
        }   

        
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the user to delete
        $user = $this->user->find($id);
        // check if user fetched exists 
        if($user['count'] != 1) {
            // send to 404 with error
            $data['error'] = "user queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            // check if request is POST
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->user->delete($id);
                set_sess("message_info", "user deleted successfully");
                redirect_to('users');
            } else {
                // load view to confirm delete
                $data['user'] = $user['data'];
                $this->view('users/delete', $data);
            }
        }
    }



















    public function profile()
    {
        // iniatilize data
        $data = [];


        // check to see if user is logged in
        if(get_sess('logged_in')) {
            // get id of the logged in person
            $id = get_sess('logged_in_user_id');

            // fetch user with id
            $user = $this->user->find($id);

            // check if null is returned
            if($user['count']>0) {
                // not null, load view with user
                $data['user'] = $user['data'];
                $this->view('users/profile', $data);
            } else {
                // load 404 with error
                $data['error'] = "User queried for does not exist";
                $this->view('errors/404', $data);
            }
        } else {
            // load 404 with error
            $data['error'] = "You have to be logged in to access user profile";
            $this->view('errors/404', $data);
        }
    }






















    public function logout()
    {
        destroy_sess();
        redirect_to('users/login');
    }



















    public function roles($id)
    {
        // iniatilize data
        $data = [];
        // get the user to update rolles for
        $user = $this->user->find($id);
        // check if user fetched exists 
        if($user['count'] != 1) {
            // send to 404 with error
            $data['error'] = "user queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {

            // get roles 
            $data['roles'] = $this->role->all();

            // get this users roles
            $rls = $this->user->getRoles($id);
            $rl_array = [];
            foreach ($rls as $value) {
                $rl_array[] = $value->role_id;
            }
            $data['user_roles'] = $rl_array;


            // check if request is POST
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $roles = $_POST['users_role'];

                $data_to_store = [];

                // print_r($roles);

                foreach ($roles as $value) {
                    $data_to_store[] = ['user_id'=>$id, 'role_id'=>$value, 'user_role_created_by' => get_sess('logged_in_user_id')];
                }

                // clear roles already there
                if($this->user->clear_roles($id)) {
                    // save the new roles
                    if($this->user->store_roles($data_to_store)) {
                        // redirect to user show
                        set_sess("message_success", "User roles updated successfully");
                        redirect_to('users/show/'.$id);
                    } else {
                        set_sess('message_error', 'Previous user role cleared, new ones not added, please try again');
                        redirect_to('users/roles/'.$id);
                    }
                } else {
                    set_sess('message_error', 'Roles not updated, please try again');
                    redirect_to('users/roles/'.$id);
                }
                   
            } else {
                // load view to confirm delete
                $data['user'] = $user['data'];
                $this->view('users/edit_user_roles', $data);
            }
        }
    }


























    public function settings()
    {

        $data = [
            'form_error'=> '',
            'user_name'=> '',
            'user_name_err' => '',
            'user_email' => '',
            'user_email_err' => '',
            'user_password' => '',
            'user_password_err' => '',
            'user_confirm_password' => '',
            'user_confirm_password_err' => '',
            'user_role' => '',
            'user_role_err' => '',
            'role_name' => '',
            'role_name_err' => '',
            'category_name' => '',
            'category_name_err' => '',

            'form_error' => '',
            'menu_item' => '',
            'menu_item_err' => '',
            'menu_category_id' => '',
            'menu_category_id_err' => '',
            'menu_description' => '',
            'menu_description_err' => '',
            'menu_cost' => '',
            'menu_cost_err' => '',
            'menu_price' => '',
            'menu_price_err' => '',
        ];

        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");      
            return ;     
        }


        $id = get_sess('logged_in_user_id');


        $user = $this->user->find($id)['data'];
        $data['user'] = $user;

        if($user->user_role_id == 1) {

            $users = $this->user->getAll();
            $data['users'] = $users;

            $menus = $this->menu->getAll();
            $data['menus'] = $menus;

            $roles = $this->role->getAll();
            $data['roles'] = $roles;

            $categorys = $this->category->getAll();
            $data['categorys'] = $categorys;

            $this->view('users/admin', $data);

        } else {

            // role
            $role = $this->role->find($user->user_role_id);
            $data['role'] = $role['data'];

            // role
            $total = $this->receipt->get_total_for_user($user->id);
            $data['total'] = $total;
            
            // customers
            $singleorder = $this->singleorder->get_users_orders($id);
            $data['singleorders'] = $singleorder;

            $this->view('users/show', $data);
        }
        
    }































    public function menucreate()
    {
        // iniatilaize data
        $data = [
            'form_error' => '',
            'menu_item' => '',
            'menu_item_err' => '',
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
                    $this->view('users/settings', $data);
                }
            } else {
                // else load view with errors
                $this->view('users/settings', $data);
            }
        } else {
            // load view
            $this->view('users/settings', $data);
        } 

    }















        public function activitys($id)
         {

            $id = get_sess('logged_in_user_id');

            if(!$id) {
                redirect_to('users/login');
            }


            $user = $this->user->find($id)['data'];
            $data['user'] = $user;


             if($user->user_role_id != 1) {
                set_sess('message_info', 'You have to be an admin to access that');
                redirect_to('');
            }

            

            $activitys = $this->activity->get_for_user($id);
            $data['activitys'] = $activitys;

            $this->view('users/useractivity', $data);

        }



















        public function activate($id)
        {
            $data = [];

            $user = $this->user->find($id)['data'];
            $data['user'] = $user;

            if($_SERVER['REQUEST_METHOD'] == "POST") {
                if($user->user_active) {
                    $message = "User deactivated successfully";
                    $data_to_update = ['user_active' => 0];
                } else {
                    $message = "User activated successfully";
                    $data_to_update = ['user_active' => 1];
                }

                $this->user->update($id, $data_to_update);
                set_sess('message_success', $message);
                redirect_to('users/activate/'.$id);
            } else {
                $this->view('users/activate', $data);
            }
        }
             
         
    }










    





