<?php 



/**
 * Home controller
 */
class Indexs extends MainController
{
    
    private $user;
    private $role;
    private $menu;
    private $category;

    public function __construct()
    {
        
        $this->user =$this->model('User');
        $this->role =$this->model('Role');
        $this->menu =$this->model('Menu');
        $this->category =$this->model('category');

        // first time, create admin user
        $user = $this->user->getAll();
        if($user['count'] == 0) {
            $data_to_store_user = [
                // 'id' => 1,
                'user_name' => "Francis",
                'user_email' => "kariukifrancis100@gmail.com",
                'user_password' => password_hash('password', PASSWORD_DEFAULT),
                'user_role_id' => 1
            ];

            $this->user->add($data_to_store_user);
        }
        // create admin role
        $roles = $this->role->getAll();
        if($roles['count'] == 0) {
            $data_to_store_admin = [
                'role_name' => "Admin",
                'role_by' => 1
            ];
            $this->role->add($data_to_store_admin);

            $data_to_store_cashier = [
                'role_name' => "Cashier",
                'role_by' => 1
            ];
            $this->role->add($data_to_store_cashier);

        }

        if(!get_sess('logged_in')) {
            // set_sess("message_error", "You have to be logged to access this app");
            redirect_to("users/login");            
        }
        
    }



    public function home()
    {
        if(!get_sess('logged_in')) {
            redirect_to("users/login");            
        }
        $data = [];

        $data['menus'] = $this->menu->getAll();
        $data['categorys'] = $this->category->getAll();

    	$this->view('home', $data);
    }


    

    
}