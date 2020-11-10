<?php 


function url_to($path)
{
    echo URL_ROOT . $path;
}



function post_data($dt)
{
    $dt = $_POST[$dt];
    $dt = trim($dt);
    $dt = stripslashes($dt);
    $dt = htmlspecialchars($dt);
    return $dt;
}







function post_data_array($dt)
{
    $rt = [];
    foreach ($dt as $value) {
        $value = $_POST[$value];
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $rt[] = $value;
    }
    
    return $rt;
}




function proper_case($value)
{
    return ucwords(strtolower($value));
}




function redirect_to($path)
{
    header("Location:".URL_ROOT.$path);
    
}





function slugify($string){
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
}




function include_layouts($path)
{
    return include_once "app/tools/layouts/" . $path;
}

function include_path($path)
{
    return "app/tools/layouts/" . $path;
}




function get_img($path) {
    return "app/tools/assets/images/" . $path;
}




function get_img_path() {
    return "app/tools/assets/images/";
}




// model in view

function miv($model)
{
    if(file_exists("app/models/".$model.".php")) {
        include_once "app/models/".$model.".php";
        $model = new $model();
        return $model;
    } else {
        die('model from view not found');;
    }
}








// function for local datetime input

function format_date_for_input($value)
{
    $df = 'Y-m-d\TH:i';
    if($value) {
        $php_timestamp = strtotime($value);
        $html_datetime_string = date($df, $php_timestamp);
        return $html_datetime_string; 
    } else {
        return date($df);
    }
    
}









// get age
function getAge($date)
{
     $date = new DateTime($date);
     $now = new DateTime();
     $interval = $now->diff($date);
     return $interval->y;
}








function getFullAge($date)
{
    $date1 = new DateTime($date);
    $date2 = new DateTime();
    $interval = $date1->diff($date2);
    $difference = "";
    if($interval->y > 0) {
        $difference = $interval->y . " years, " . $interval->m." months, ".$interval->d." days";
    } elseif ($interval->m > 0) {
        $difference = $interval->m." months, ".$interval->d." days";
    } elseif ($interval->d > 0) {
        $difference = $interval->d." days " . $interval->h . " hours " . $interval->i . " minutes ";
    } elseif ($interval->h > 0) {
        $difference =  $interval->h . " hours " . $interval->i . " minutes ";
    } else {
        $difference =  $interval->i . " minutes ";
    }
    return $difference;
     
 
}











function formatedDate($date)
{
    $dt = new DateTime($date);
    return date_format($dt,"D, d M Y,  H:i:s");
}


function formatedDateshow($date)
{
    $dt = new DateTime($date);
    return date_format($dt,"D, d M Y");
}










function random_string()
{
  return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,50) . time();
}




































function uploadImage($img, $path)
{

      $imagename = $_FILES['new_image']['name'];
      $source = $_FILES['new_image']['tmp_name'];
      $target = $path.$imagename;
      move_uploaded_file($source, $target);

      $imagepath = $imagename;
      $save = "images/" . $imagepath; //This is the new file you saving
      $file = "images/" . $imagepath; //This is the original file

      list($width, $height) = getimagesize($file) ;

      $modwidth = 500;

      $diff = $width / $modwidth;

      $modheight = $height / $diff;
      $tn = imagecreatetruecolor($modwidth, $modheight) ;
      $image = imagecreatefromjpeg($file) ;
      imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;

      imagejpeg($tn, $save, 100) ;
    /*
      $save = "images/sml_" . $imagepath; //This is the new file you saving
      $file = "images/" . $imagepath; //This is the original file

      list($width, $height) = getimagesize($file) ;

      $modwidth = 80;

      $diff = $width / $modwidth;

      $modheight = $height / $diff;
      $tn = imagecreatetruecolor($modwidth, $modheight) ;
      $image = imagecreatefromjpeg($file) ;
      imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;

      imagejpeg($tn, $save, 100) ; */
    echo "Large image: <img src='images/".$imagepath."'><br>";
    //echo "Thumbnail: <img src='images/sml_".$imagepath."'>";
    
}

