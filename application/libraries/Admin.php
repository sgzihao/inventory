<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin {

    private $CI;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('session');
        $this->CI->load->model('user/user', 'model_user');
    }

    /**
     * Create a user account
     *
     * @access	public
     * @param	string
     * @param	string
     * @param	bool
     * @return	bool
     */
    function create($user = '', $password = '', $auto_login = true) {
        
        //Make sure account info was sent
        if ($user == '' OR $password == '') {
            return false;
        }

        //Check against user table
        if ($this->CI->model_user->checkUser($user))
            return false;

        //Encrypt password
        $password = md5($password);
        
        $user_id = $this->CI->model_user->insert($user,$password);
        if ($user_id < 1)
            return false;

        //Automatically login to created account
        if ($auto_login) {
            //Destroy old session
            $this->CI->session->sess_destroy();
            //Create a fresh, brand new session
            $this->CI->session->sess_create();

            //Set session data
            $this->CI->session->set_userdata(array('id' => $user_id, 'username' => $user));

            //Set logged_in to true
            $this->CI->session->set_userdata(array('logged_in' => true));
        }

        //Login was successful			
        return true;
    }

    /**
     * Delete user
     *
     * @access	public
     * @param integer
     * @return	bool
     */
    function delete($user_id) {
        
        if (!is_numeric($user_id)) {
            //There was a problem
            return false;
        }

        if ($this->CI->db->delete($this->user_table, array('id' => $user_id))) {
            //Database call was successful, user is deleted
            return true;
        } else {
            //There was a problem
            return false;
        }
    }

    /**
     * Login and sets session variables
     *
     * @access	public
     * @param	string
     * @param	string
     * @return	bool
     */
    function login($user = '', $password = '') {

        //Make sure login info was sent
        if ($user == '' OR $password == '')
            return false;
        
        
        
        //Check if already logged in
        //die(var_dump($this->CI->session->userdata('session_id')));
        if ($this->CI->session->userdata('username')) {
            //User is already logged in.
            return false;
        }

        //Check against user table
        if ($this->CI->model_user->checkUser($user)) {
            $row = $this->CI->model_user->getUser($user);
            
            //Check against password
            if (md5($password) != $row['password']) {
                return false;
            }
           
            //Destroy old session
            $this->CI->session->sess_destroy();

            //Create a fresh, brand new session
            $this->CI->session->sess_create();

            //Remove the password field
            unset($row['password']);

            //Set session data
            //$this->CI->session->set_userdata(array('username'=>$user));

            //Set logged_in to true
            $this->CI->session->set_userdata(array('logged_in' => true,'username'=>$user));

            //Login was successful			
            return true;
        } else {
            //No database result found
            return false;
        }
    }
    function auth($login, $password, $remember, $login_by_username, $login_by_email) {
        if ((strlen($login) > 0) AND (strlen($password) > 0)) {

            // Which function to use to login (based on config)
            if ($login_by_username AND $login_by_email) {
                $get_user_func = 'get_user_by_login';
            } else if ($login_by_username) {
                $get_user_func = 'get_user_by_username';
            } else {
                $get_user_func = 'get_user_by_email';
            }

            if (!is_null($user = $this->ci->users->$get_user_func($login))) { // login ok
                // Does password match hash in database?
                $hasher = new PasswordHash(
                                $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
                                $this->ci->config->item('phpass_hash_portable', 'tank_auth'));
                if ($hasher->CheckPassword($password, $user->password)) {  // password ok
                    if ($user->banned == 1) {         // fail - banned
                        $this->error = array('banned' => $user->ban_reason);
                    } else {
                        $this->ci->session->set_userdata(array(
                            'user_id' => $user->id,
                            'username' => $user->username,
                            'status' => ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
                        ));

                        if ($user->activated == 0) {       // fail - not activated
                            $this->error = array('not_activated' => '');
                        } else {            // success
                            if ($remember) {
                                $this->create_autologin($user->id);
                            }

                            $this->clear_login_attempts($login);

                            $this->ci->users->update_login_info(
                                    $user->id, $this->ci->config->item('login_record_ip', 'tank_auth'), $this->ci->config->item('login_record_time', 'tank_auth'));
                            return TRUE;
                        }
                    }
                } else {              // fail - wrong password
                    $this->increase_login_attempt($login);
                    $this->error = array('password' => 'auth_incorrect_password');
                }
            } else {               // fail - wrong login
                $this->increase_login_attempt($login);
                $this->error = array('login' => 'auth_incorrect_login');
            }
        }
        return FALSE;
    }
    /**
     * Logout user
     *
     * @access	public
     * @return	void
     */
    function logout() {
        //Destroy session
        $this->CI->session->sess_destroy();
    }

}

/* End of file admin.php */
/* Location: ./application/libraries/admin.php */