<?php 



/**
 * Main controller
 */
class MainController
{
    /**
     * summary
     */
    public function __construct()
    {
        // echo 'from main controller';
    }



    public function view($view, $data=[])
    {
    	if(file_exists('app/views/'.$view.'.php')) {
    		include_once 'app/views/'.$view.'.php';
    	} else {
    		die('view does not exist');
    	}
    }



    public function model($model)
    {
    	if(file_exists('app/models/'.$model.'.php')) {
            include_once 'app/models/'.$model.'.php';
            return new $model();
        } 
    }
}