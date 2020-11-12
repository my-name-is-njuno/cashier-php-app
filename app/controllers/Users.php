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
    private $post;
    private $activity;
    private $singleorder;
    private $receipt;

    public function __construct()
    {
    	// load models
        $this->user = $this->model('User');
        $this->role = $this->model('Role');
        $this->category = $this->model('Category');
        $this->post = $this->model('Post');
        $this->activity = $this->model('Activity');
    }


    // users home page
    public function home()
    {
          if(!get_sess('logged_in')) {
              set_sess("message_error", "You have to be logged in to access this functionality");
              redirect_to("users/login");
              return ;
          }
        $data = [];
        if(get_sess('logged_in_user_id') == 1) {
            $data['user'] = $this->user->find(get_sess('logged_in_user_id'))['data'];
            $data['users'] = $this->user->all_for_show()['data'];
            $this->view('users/index', $data);
        } else {
            set_sess('message_error', 'You have to be admin to access that page');
            redirect_to('users/profile');
        }
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
                    redirect_to('users/show/'.$stored);
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
                                redirect_to('users');
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

        if (get_sess('logged_in_user_id') != 1) {
            set_sess('message_error', 'You cannot view other people profiles');
            redirect_to('users/profile');
        }

        // fetch user with id
        $user = $this->user->find_for_show($id);
        if($user['count']) {
            $blogs = $this->user->get_user_blogs($id);
            // not null, load view with user
            $data['user'] = $user['data'];
            $data['blogs'] = $blogs['data'];
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
            'user_email'=> '',
            'user_role'=> '',
            'user_name_err' => '',
            'user_role_err'=> '',
            'user_email_err' => '',
        ];

        // get user to edit
        $user = $this->user->find($id);
        
        

        // check if null is returned
        if($user['count']==0) {
            // load 404 with error
            $data['error'] = "User queried for does not exist";
            $this->view('errors/404', $data);
            return;
        }

        // set user data 
        $data['user_name'] = $user['data']->user_name;
        $data['user_email'] = $user['data']->user_email;
        $data['user_role'] = $user['data']->user_role_id;

        // pass user to view
        $data['user'] = $user['data'];
        $data['roles'] = $this->role->getAll()['data'];


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
                    $data['user_name_err'] = "Name is already taken";
                    $error = true;
                } else {
                    $data_to_update['user_name'] = $name; 
                }
            } else {
                $data['user_name_err'] = "Name is required";
                $error = true;
            }

            // validate role
            $role = post_data('user_role');
            if(!empty($role)) {
                $data['user_role'] = $role;
                $data_to_update['user_role_id'] = $role;
            } else {
                $data['user_role_err'] = "User role is required.";
                $error = true;
            }

            $data['error'] = $error;

            // check for validation errors 
            if($error == false) {
                // store data to db and redirect to users home
                $updated = $this->user->update($id, $data_to_update);
                if($updated) {
                    // set session
                    set_sess("message_success", "User has been updated successfully");
                    // redirect
                    redirect_to('users/show/'.$id);
                } else {
                    // load view with data with errors, from db
                    set_sess("message_error", "User not updated to db, db error, try again");
                    redirect_to('users/edit/'.$id);
                }

            } else {
                // else load view with errors
                set_sess("message_error", "errors in form");
                // echo '<pre>';
                // die(print_r($data));
                // echo '</pre>';
                $this->view('users/edit', $data);
            }
        } else {
            // load view
            $this->view('users/edit', $data);
        }
    }







    public function delete($id)
    {

        if(get_sess('logged_in_user_id') != 1) {
            set_sess('message_info', 'You need to be admin to access this page');
            redirect_to('users/profile');
            return;
        }
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
            $data['deletable'] = true;
            $blogs = $this->user->get_user_blogs($id);
            if($blogs['count'] > 0) {
                $data['deletable'] = false;
            }
            // check if request is POST
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                if($blogs['count'] > 0) {
                    foreach ($blogs['data'] as $v) {
                        $this->post->delete($v->id);
                    }
                }
                $this->user->delete($id);
                set_sess("message_info", "User deleted successfully");
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
            $user = $this->user->find_for_show($id);

            // check if null is returned
            if($user['count']) {
                $blogs = $this->user->get_user_blogs($id);
                // not null, load view with user
                $data['user'] = $user['data'];
                $data['blogs'] = $blogs['data'];
                $this->view('users/show', $data);
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





















    








































    }
