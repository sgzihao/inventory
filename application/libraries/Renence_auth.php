<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 
 * 
 */
class Renence_auth {

    private $error = array();

    /**
     * @desc construct function
     * 
     */
    public function __construct() {

        $this->ci = & get_instance();

        $this->ci->load->config('renence_auth', TRUE);

        $this->ci->load->library('session');
        $this->ci->load->database();
        $this->ci->load->model('renence_auth/users');
    }

    public function login($login, $password, $remember=false) {
        
    }

    /**
     * Logout user from the site
     *
     * @return	void
     */
    public function logout() {
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
    public function isLogin($activated = TRUE) {
        return $this->ci->session->userdata('status') === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
    }

    /**
     * Get user_id
     *
     * @return	string
     */
    public function getUserId() {
        //return $this->ci->session->userdata('user_id');
    }

    /**
     * Get username
     *
     * @return	string
     */
    public function getUsername() {
        //return $this->ci->session->userdata('username');
    }

    /**
     * Create new user on the site and return some data about it:
     * user_id, username, password, email, new_email_key (if any).
     *
     * @param	string
     * @param	string
     * @param	string
     * @param	bool
     * @return	array
     */
    public function createUser($username, $email, $password) {
        
    }

    /**
     * Check if username available for registering.
     * Can be called for instant form validation.
     *
     * @param	string
     * @return	bool
     */
    public function isUsernameAvailable($username) {
        //return ((strlen($username) > 0) AND $this->ci->users->is_username_available($username));
    }

    /**
     * Check if email available for registering.
     * Can be called for instant form validation.
     *
     * @param	string
     * @return	bool
     */
    public function isEmailAvailable($email) {
        //return ((strlen($email) > 0) AND $this->ci->users->is_email_available($email));
    }

    /**
     * Change email for activation and return some data about user:
     * user_id, username, email, new_email_key.
     * Can be called for not activated users only.
     *
     * @param	string
     * @return	array
     */
    public function changeEmail($email) {
        
    }

    /**
     * Activate user using given key
     *
     * @param	string
     * @param	string
     * @param	bool
     * @return	bool
     */
    public function activateUser($user_id, $activation_key, $activate_by_email = TRUE) {
        
    }

    /**
     * Set new password key for user and return some data about user:
     * user_id, username, email, new_pass_key.
     * The password key can be used to verify user when resetting his/her password.
     *
     * @param	string
     * @return	array
     */
    public function forgotPassword($login) {
        
    }

    /**
     * Replace user password (forgotten) with a new one (set by user)
     * and return some data about it: user_id, username, new_password, email.
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function resetPassword($user_id, $new_pass_key, $new_password) {
        
    }

    /**
     * Change user password (only when user is logged in)
     *
     * @param	string
     * @param	string
     * @return	bool
     */
    public function changePassword($old_pass, $new_pass) {
        
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @param	string
     * @return	bool
     */
    public function deleteUser($password) {
        
    }

    /**
     * Get error message.
     * Can be invoked after any failed operation such as login or register.
     *
     * @return	string
     */
    public function getErrorMessage() {
        return $this->error;
    }

}

/* End of file Renence_auth.php */
/* Location: ./application/libraries/Renence_auth.php */