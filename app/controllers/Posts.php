<?php



/**
 * posts controller
 */
class Posts extends MainController
{
    /**
     * manage crud operations for posts
     */

    // iniatialize models
    private $post;
    private $category;
    private $comments;
    private $receipt;


    public function __construct()
    {
        // check if user is logged in
        if(!get_sess('logged_in')) {
            set_sess("message_error", "You have to be logged in to access this functionality");
            redirect_to("users/login");
        }
        // load models
        $this->post = $this->model('Menu');
        $this->category = $this->model('Category');
        $this->receipt = $this->model('Receipt');
        $this->comments = $this->model('Order');
    }



    // posts home page
    public function home($page=1)
    {
    	// initialize data
        $data = [];


        // set page
        if(!$page) {$page = 1;}


        // get paginated data
        $dt = $this->post->getAllPaginated(10, $page);
        // check if anything has been returned
        if($dt) {
            $total_pages = $dt[0];
            $posts_data = $dt[1];
            $total_results = $dt[2];
        } else {
            $total_pages = 0;
            $posts_data = [];
            $total_results = 0;
        }

        // get all posts
        $data['posts'] = $posts_data;
        $data['total_results'] = $total_results;
        $data['total_pages'] = $total_pages;

    	// load view with data
    	$this->view('posts/index', $data);
    }




    public function create()
    {
    	// iniatilaize data
    	$data = [
            'form_error' => '',
        		'post_title' => '',
        		'post_title_err' => '',
            'post_image' => '',
            'post_image_err' => '',
            'post_category_id' => '',
            'post_category_id_err' => '',
            'post_content' => '',
            'post_content_err' => '',
            'post_read_mins' => '',
            'post_read_mins_err' => '',
            'post_by' => '',
            'post_by_err' => '',
    	];

        // get categories
        $cats = $this->category->getAll();
        if($cats['count'] == 0) {
            set_sess('message_info', 'Before adding post item, first add post category here');
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
    		$item = post_data('post_title');
    		if(!empty($item)) {
    			$data['post_title'] = $item;
    			if($this->post->checkIfItemExist($item)) {
    				$data['post_title_err'] = "item of the post entered is already taken";
                    $error = true;
    			} else {
    				$data_to_store['post_title'] = proper_case($item);
    			}
    		} else {
    			$data['post_title_err'] = "item of the post is required";
                $error = true;
    		}


            $img = $_FILES['post_image'];
            if($img['size'] > 0) {
                  $handle = new \Verot\Upload\Upload($_FILES['post_image']);
                  $new_name = strtolower(slugify($item)).time();
                  $handle->file_new_name_body   = $new_name ;
                  $handle->image_resize         = true;
                  $handle->image_x              = 400;
                  $handle->image_y              = 300;
                  $handle->process(get_img_path().'post/');
                  if ($handle->processed) {
                    $data_to_store['post_image'] = $new_name  . ".".$handle->file_dst_name_ext;
                    $handle->clean();
                  } else {
                    $data['post_image_err'] = $handle->error;
                    $error = true;
                  }
            } else {
                $data['post_image_err'] = "Menu for the image is required";
                $error = true;
            }



            $category_id = post_data('post_category_id');
            if(!empty($category_id)) {
                $data['post_category_id'] = $category_id;
                $data_to_store['post_category_id'] = proper_case($category_id);
            } else {
                $data['post_category_id_err'] = "category of the post is required";
                $error = true;
            }


            $content = post_data('post_content');
            if(!empty($content)) {
                $data['post_content'] = $content;
                $data_to_store['post_content'] = proper_case($content);
            } else {
                $data['post_content_err'] = "content of the post is required";
                $error = true;
            }


            $read_mins = post_data('post_read_mins');
            if(!empty($read_mins)) {
                $data['post_read_mins'] = $read_mins;
                $data_to_store['post_read_mins'] = proper_case($read_mins);
            } else {
                $data['post_read_mins_err'] = "read_mins of the post is required";
                $error = true;
            }




    		// check for validation errors
    		if(!$error) {
    			// store data to db and redirect to posts home
          $data_to_store['post_by'] = get_sess('logged_in_user_id');
    			$stored = $this->post->add($data_to_store);
    			if($stored) {
                    // set session
                    set_sess("message_success", "Menu has been added successfully");
                    // redirect
    				redirect_to('posts/show/'.$stored);
    			} else {
    				// load view with data with errors, from db
    				$data['post_name_err'] = "post not added to db, db error, try again";
    				$this->view('posts/create', $data);
    			}
    		} else {
    			// else load view with errors
    			$this->view('posts/create', $data);
    		}
    	} else {
    		// load view
    		$this->view('posts/create', $data);
    	}
    }





    // post profile, provide id
    public function show($id)
    {
    	// iniatilize data
    	$data = [];

    	// fetch post with id
    	$post = $this->post->find($id);

    	// check if null is returned
    	if($post['count']>0) {
            // check if post has sales
            $sales = $this->comments->get_commentss_for($id);
            $data['customers'] = $sales;

            // not null, load view with post
    		$data['post'] = $post['data'];

    		$this->view('posts/show', $data);
    	} else {
    		// load 404 with error
    		$data['error'] = "post queried for does not exist";
    		$this->view('errors/404', $data);
            return;
    	}
    }





    public function edit($id)
    {
        // initialize data
        $data = [
            'form_error' => '',
            'post_title' => '',
            'post_title_err' => '',
            'post_image' => '',
            'post_image_err' => '',
            'post_category_id' => '',
            'post_category_id_err' => '',
            'post_content' => '',
            'post_content_err' => '',
            'post_read_mins' => '',
            'post_read_mins_err' => '',
            'post_by' => '',
            'post_by_err' => '',
        ];



        // get categories
        $data['categorys'] = $this->category->all();


        // get the post to edit
        $post = $this->post->find($id);
        // check if post fetched exists
        if($post['count']<1) {
            // send to 404 with error
            $data['error'] = "post queried for does not exist";
            $this->view('errors/404', $data);
            return;
        }

        // load post name in data array
        $data['post'] = $post['data'];
        $data['post_title'] = $post['data']->post_title;
        $data['post_read_mins'] = $post['data']->post_read_mins;
        $data['post_by'] = $post['data']->post_by;
        $data['post_content'] = $post['data']->post_content;
        $data['post_category_id'] = $post['data']->post_category_id;


        // check if post method
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            // iniatialize data to update
            $data_to_update = [];

            // validate and set errors
            // track errors
            $errors = false;

            // validate
            $item = post_data('post_title');
            if(!empty($item)) {
                $data['post_title'] = $item;
                if($this->post->checkIfItemExistEdit($id, $item)) {
                    $data['post_title_err'] = "item of the post entered is already taken";
                    $error = true;
                } else {
                    $data_to_update['post_title'] = proper_case($item);
                }
            } else {
                $data['post_title_err'] = "item of the post is required";
                $error = true;
            }


            $img = $_FILES['post_image'];
            if($img['size'] > 0) {
                  $handle = new \Verot\Upload\Upload($_FILES['post_image']);
                  $new_name = strtolower(slugify($item)).time();
                  $handle->file_new_name_body   = $new_name ;
                  $handle->image_resize         = true;
                  $handle->image_x              = 400;
                  $handle->image_y              = 300;
                  $handle->process(get_img_path().'post/');
                  if ($handle->processed) {
                    $data_to_update['post_image'] = $new_name  . ".".$handle->file_dst_name_ext;
                    $handle->clean();
                  } else {
                    $data['post_image_err'] = $handle->error;
                    $error = true;
                  }
            } else {
                $data['post_image_err'] = "Menu for the image is required";
                $error = true;
            }


            $category_id = post_data('post_category_id');
            if(!empty($category_id)) {
                $data['post_category_id'] = $category_id;
                $data_to_update['post_category_id'] = proper_case($category_id);
            } else {
                $data['post_category_id_err'] = "category of the post is required";
                $error = true;
            }


            $content = post_data('post_content');
            if(!empty($content)) {
                $data['post_content'] = $content;
                $data_to_update['post_content'] = proper_case($content);
            } else {
                $data['post_content_err'] = "content of the post is required";
                $error = true;
            }


            $read_mins = post_data('post_read_mins');
            if(!empty($read_mins)) {
                $data['post_read_mins'] = $read_mins;
                $data_to_update['post_read_mins'] = proper_case($read_mins);
            } else {
                $data['post_read_mins_err'] = "read_mins of the post is required";
                $error = true;
            }





            // check if there is any validation errors
            if(empty($data['post_name_err'])) {
                // store
                $updated = $this->post->update($id, $data_to_update);
                if($updated) {
                    // set session message and redirect to posts home
                    set_sess('message_success', "post Updated successfully");
                    redirect_to('posts/show/' . $id);
                } else {
                    // return views with errors
                    $data['post_name_err'] = "post was not updated, db error, please try again";
                    $this->view('posts/edit', $data);
                }
            } else {
                // return views with errors
                $this->view('posts/edit', $data);
            }

        } else {
            // load view if request is get
            $this->view('posts/edit', $data);
        }
    }







    public function delete($id)
    {
        // initialize data;
        $data = [];
        // get the post to delete
        $post = $this->post->find($id);
        // check if post fetched exists
        if($post['count'] != 1) {
            // send to 404 with error
            $data['error'] = "post queried for does not exist";
            $this->view('errors/404', $data);
            return;
        } else {
            // check if post has sales
            $sales = $this->comments->get_commentss_for($post['data']->id);
            $data['sales'] = $sales;
            // check if request is postt
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                // delete and redirect
                $this->post->delete($id);
                set_sess("message_info", "post deleted successfully");
                redirect_to('posts');
            } else {
                // load view to confirm delete
                $data['post'] = $post['data'];
                $this->view('posts/delete', $data);
            }
        }
    }













    public function find_with_ajax()
    {
        $query = post_data('query');
        $data = $this->post->find_with_ajax($query);

        $return_arr = [];
        if($data['count'] > 0) {
            $data_to_return = $data['data'];
            $return_arr['by'] = $data_to_return->post_by;
        }

        echo json_encode($return_arr);
    }








































}
