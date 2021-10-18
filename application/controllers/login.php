<?php

class Login extends CI_Controller {

    function __construct() {
        parent::CI_Controller();
    }

    function index() {

        if ($this->session->userdata('logged_in')) {
            echo 'User logged in as ' . $this->session->userdata('username');
        } else {
            echo 'User not logged in';
        }

        if (!$this->session->userdata('logged_in')) {
           
        }
        //EOF Login user
        //BOF User table
        if ($this->session->userdata('logged_in')) {
            ;
        }
        //EOF User table
    }

    function create() {
    }

    function delete($user_id) {
        
    }

    function login() {
        
    }

    function logout() {
        
    }

}

?>