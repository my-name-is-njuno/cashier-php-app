<?php



/**
 * Home controller
 */
class Indexs extends MainController
{

    private $user;
    private $role;
    private $post;
    private $category;

    public function __construct()
    {
        $this->user =$this->model('User');
        $this->role =$this->model('Role');
        $this->category =$this->model('Category');
        $this->post =$this->model('Post');

        // first time, create admin user
        $user = $this->user->getAll();
        if($user['count'] == 0) {
            $data_to_store_user = [
                // 'id' => 1,
                'user_name' => "Petero",
                'user_email' => "pmnjuno@gmail.com",
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
                'role_name' => "Writer",
                'role_by' => 1
            ];
            $this->role->add($data_to_store_cashier);

        }



    }




    public function home()
    {
        $data = [];
        // $data['posts'] = $this->post->getAll();
    	  $this->view('home', $data);
    }







    public function about()
    {
        $data = [];
    	  $this->view('about', $data);
    }










    public function admin()
    {
        if(!get_sess('logged_in')) {
            redirect_to("users/login");
        }
        $data = [];

        $data['posts'] = $this->post->getAll();
        $data['categorys'] = $this->category->getAll();

    	$this->view('home', $data);
    }





}
