<?php 



/**
 * errors controller
 */
class errors extends MainController
{
    
    public function __construct()
    {
        
    }


    // home page for derpartment
    public function home()
    {
    	// initialize data
    	$data = [];

    	// load view with data 
    	$this->view('errors/index', $data);
    }





    public function notfound()
    {
        $this->view('errors/404');
    }





    public function notauthorised()
    {
        $this->view('errors/401');
    }






    




}