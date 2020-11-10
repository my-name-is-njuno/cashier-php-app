<?php 
    session_set_cookie_params(0);
    
    session_start();

    function set_sess($sess, $value) 
    {
        $_SESSION[$sess] = $value;
    }

    function get_sess($sess)
    {
        if(isset($_SESSION[$sess])) {
            return $_SESSION[$sess];
        } else {
            return false;
        }
        
    }

    function unset_sess($sess) 
    {
        unset($_SESSION[$sess]);
    }

    function destroy_sess() 
    {
        session_destroy();
    }



    function login_user($id, $username, $email, $role)
    {
        set_sess('logged_in_user_id', $id);
        set_sess('logged_in_user_name', $username);
        set_sess('logged_in_user_email', $email);
        set_sess('logged_in_user_role', $role);
        set_sess('logged_in', true);
    }



    function logout_user()
    {
        session_destroy();
    }