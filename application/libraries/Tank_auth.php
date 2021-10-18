<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

/**
 * Tank_auth
 *
 * Authentication library for Code Igniter.
 *
 * @package		Tank_auth
 * @author		Ilya Konyukhov (http://konyukhov.com/soft/)
 * @version		1.0.9
 * @based on	DX Auth by Dexcell (http://dexcell.shinsengumiteam.com/dx_auth)
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 */
class Tank_auth {

    private $error = array();

    function __construct() {
        $this->ci = & get_instance();

        $this->ci->load->config('tank_auth', TRUE);

        $this->ci->load->library('session');
        $this->ci->load->database();
        $this->ci->load->model('tank_auth/users');

        // Try to autologin
        $this->autologin();
    }

    /**
     * Login user on the site. Return TRUE if login is successful
     * (user exists and activated, password is correct), otherwise FALSE.
     *
     * @param	string	(username or email or both depending on settings in config file)
     * @param	string
     * @param	bool
     * @return	bool
     */
    function login($login, $password, $remember, $login_by_username, $login_by_email) {
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
     * Logout user from the site
     *
     * @return	void
     */
    function logout() {
        $this->delete_autologin();

        // See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
        $this->ci->session->set_userdata(array('user_id' => '', 'username' => '', 'status' => ''));

        $this->ci->session->sess_destroy();
    }

    /**
     * Check if user logged in. Also test if user is activated or not.
     *
     * @param	bool
     * @return	bool
     */
    function is_logged_in($activated = TRUE) {
        return $this->ci->session->userdata('status') === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
    }

    /**
     * Login user automatically if he/she provides correct autologin verification
     *
     * @return	void
     */
    private function autologin() {
        if (!$this->is_logged_in() AND !$this->is_logged_in(FALSE)) {   // not logged in (as any user)
            $this->ci->load->helper('cookie');
            if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE)) {

                $data = unserialize($cookie);

                if (isset($data['key']) AND isset($data['user_id'])) {

                    $this->ci->load->model('tank_auth/user_autologin');
                    if (!is_null($user = $this->ci->user_autologin->get($data['user_id'], md5($data['key'])))) {

                        // Login user
                        $this->ci->session->set_userdata(array(
                            'user_id' => $user->id,
                            'username' => $user->username,
                            'status' => STATUS_ACTIVATED,
                        ));

                        // Renew users cookie to prevent it from expiring
                        set_cookie(array(
                            'name' => $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
                            'value' => $cookie,
                            'expire' => $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
                        ));

                        $this->ci->users->update_login_info(
                                $user->id, $this->ci->config->item('login_record_ip', 'tank_auth'), $this->ci->config->item('login_record_time', 'tank_auth'));
                        return TRUE;
                    }
                }
            }
        }
        return FALSE;
    }

}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */