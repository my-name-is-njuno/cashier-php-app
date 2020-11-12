<?php



/**
 * main
 */
class Main
{

    public function __construct()
    {
        $url = isset($_GET['url']) ? $_GET['url']: NULL;
		if($url) {
			$url = rtrim($url,'/');
			$url = explode('/', filter_var($url, FILTER_SANITIZE_URL));

			$ctrl = ucwords(strtolower($url[0]));
      if ($ctrl == 'About') {
          // code...
          include_once 'app/controllers/Indexs.php';
    			$home = new Indexs();
    			$home->about();
        } else {
          if (file_exists('app/controllers/'.$ctrl.'.php')) {
    				include_once 'app/controllers/'.$ctrl.'.php';
    				$controller = new $ctrl();
    				if(isset($url[2])) {
    					if(method_exists($controller, $url[1])) {
    						$controller->{$url[1]}($url[2]);
    					}else {
    					 	die("404: You are lost my Nigga");
    					}
    				} else {
    					if(isset($url[1])) {
    						if(method_exists($controller, $url[1])) {
    					 		$controller->{$url[1]}();
    					 	} else {
    					 		die("404: You are lost my Nigga");
    					 	}
    					} else {
    						$controller->home();
    					}
    				}
    			} else {
    				die('404: Not Found My Nigga');
    			}
        }


		} else {
			include_once 'app/controllers/Indexs.php';
			$home = new Indexs();
			$home->home();
		}
    }
}
